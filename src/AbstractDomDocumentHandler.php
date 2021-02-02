<?php

namespace WsdlToPhp\DomHandler;

use DOMAttr;
use DOMDocument;
use DOMElement;
use DOMNameSpaceNode;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use InvalidArgumentException;

abstract class AbstractDomDocumentHandler
{
    protected DOMDocument $domDocument;

    protected ?ElementHandler $rootElement;

    public function __construct(DOMDocument $domDocument)
    {
        $this->domDocument = $domDocument;
        $this->initRootElement();
    }

    public function getHandler($node, int $index = -1): AbstractNodeHandler
    {
        if ($node instanceof DOMElement) {
            return $this->getElementHandler($node, $this, $index);
        }
        if ($node instanceof DOMAttr) {
            return $this->getAttributeHandler($node, $this, $index);
        }
        if ($node instanceof DOMNameSpaceNode) {
            return new NameSpaceHandler($node, $this, $index);
        }

        return $this->getNodeHandler($node, $this, $index);
    }

    public function getNodeByName(string $name): ?NodeHandler
    {
        return $this->domDocument->getElementsByTagName($name)->length > 0 ? $this->getNodeHandler($this->domDocument->getElementsByTagName($name)->item(0), $this) : null;
    }

    public function getElementByName(string $name): ?ElementHandler
    {
        $node = $this->getNodeByName($name);
        if ($node instanceof AbstractNodeHandler && $node->getNode() instanceof DOMElement) {
            return $this->getElementHandler($node->getNode(), $this);
        }

        return null;
    }

    public function getNodesByName(string $name, ?string $checkInstance = null): array
    {
        $nodes = [];
        if ($this->domDocument->getElementsByTagName($name)->length > 0) {
            foreach ($this->domDocument->getElementsByTagName($name) as $node) {
                if (is_null($checkInstance) || $node instanceof $checkInstance) {
                    $nodes[] = $this->getHandler($node, count($nodes));
                }
            }
        }

        return $nodes;
    }

    public function getElementsByName(string $name): array
    {
        return $this->getNodesByName($name, DOMElement::class);
    }

    public function getElementsByNameAndAttributes(string $name, array $attributes, ?DOMNode $node = null): array
    {
        $matchingElements = $this->getElementsByName($name);
        if ((!empty($attributes) || $node instanceof DOMNode) && !empty($matchingElements)) {
            $nodes = $this->searchTagsByXpath($name, $attributes, $node);

            if (!empty($nodes)) {
                $matchingElements = $this->getElementsHandlers($nodes);
            }
        }

        return $matchingElements;
    }

    public function getElementsHandlers(DOMNodeList $nodeList): array
    {
        $nodes = [];
        if (!empty($nodeList)) {
            $index = 0;
            foreach ($nodeList as $node) {
                if ($node instanceof DOMElement) {
                    $nodes[] = $this->getElementHandler($node, $this, $index);
                    ++$index;
                }
            }
        }

        return $nodes;
    }

    public function searchTagsByXpath(string $name, array $attributes, ?DOMNode $node = null): DOMNodeList
    {
        $xpath = new DOMXPath($node ? $node->ownerDocument : $this->domDocument);
        $xQuery = sprintf("%s//*[local-name()='%s']", $node instanceof DOMNode ? '.' : '', $name);
        foreach ($attributes as $attributeName => $attributeValue) {
            if (false !== strpos($attributeValue, '*')) {
                $xQuery .= sprintf("[contains(@%s, '%s')]", $attributeName, str_replace('*', '', $attributeValue));
            } else {
                $xQuery .= sprintf("[@%s='%s']", $attributeName, $attributeValue);
            }
        }

        return $xpath->query($xQuery, $node);
    }

    public function getElementByNameAndAttributes(string $name, array $attributes): ?ElementHandler
    {
        $elements = $this->getElementsByNameAndAttributes($name, $attributes);

        return array_shift($elements);
    }

    /**
     * Find valid root node (not a comment, at least a DOMElement node).
     *
     * @throws InvalidArgumentException
     */
    protected function initRootElement()
    {
        if ($this->domDocument->hasChildNodes()) {
            foreach ($this->domDocument->childNodes as $node) {
                if ($node instanceof DOMElement) {
                    $this->rootElement = $this->getElementHandler($node, $this);

                    break;
                }
            }
        } else {
            throw new InvalidArgumentException('Document seems to be invalid', __LINE__);
        }
    }

    abstract protected function getNodeHandler(DOMNode $node, AbstractDomDocumentHandler $domDocument, int $index = -1): NodeHandler;

    abstract protected function getElementHandler(DOMElement $element, AbstractDomDocumentHandler $domDocument, int $index = -1): ElementHandler;

    abstract protected function getAttributeHandler(DOMAttr $attribute, AbstractDomDocumentHandler $domDocument, int $index = -1): AttributeHandler;
}
