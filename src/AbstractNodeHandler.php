<?php

declare(strict_types=1);

namespace WsdlToPhp\DomHandler;

use DOMElement;
use DOMNode;
use DOMNodeList;
use Traversable;

abstract class AbstractNodeHandler
{
    protected DOMNode $node;

    protected int $index;

    protected AbstractDomDocumentHandler $domDocumentHandler;

    public function __construct(DOMNode $node, AbstractDomDocumentHandler $domDocumentHandler, int $index = 0)
    {
        $this->node = $node;
        $this->index = $index;
        $this->domDocumentHandler = $domDocumentHandler;
    }

    public function getNode(): DOMNode
    {
        return $this->node;
    }

    /**
     * @return DOMNodeList<DOMElement|DOMNode>
     */
    public function getChildNodes(): DOMNodeList
    {
        return $this->getNode()->childNodes;
    }

    public function getParent(): ?AbstractNodeHandler
    {
        if ($this->getNode()->parentNode instanceof DOMNode) {
            return $this->getDomDocumentHandler()->getHandler($this->getNode()->parentNode);
        }

        return null;
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public function getDomDocumentHandler(): AbstractDomDocumentHandler
    {
        return $this->domDocumentHandler;
    }

    public function getName(): string
    {
        $name = $this->getNode()->nodeName;
        if (false !== strpos($name, ':')) {
            $name = implode('', array_slice(explode(':', $name), -1, 1));
        }

        return $name;
    }

    public function getNamespace(): ?string
    {
        $name = $this->getNode()->nodeName;
        if (false !== strpos($name, ':')) {
            return implode('', array_slice(explode(':', $name), 0, -1));
        }

        return null;
    }

    public function hasAttributes(): bool
    {
        return $this->getNode()->hasAttributes();
    }

    /**
     * @return AbstractAttributeHandler[]|AbstractNodeHandler[]
     */
    public function getAttributes(): array
    {
        return $this->getHandlers($this->getNode()->attributes);
    }

    public function hasChildren(): bool
    {
        return $this->getNode()->hasChildNodes();
    }

    /**
     * @return AbstractAttributeHandler[]|AbstractElementHandler[]|AbstractNodeHandler[]
     */
    public function getChildren(): array
    {
        return $this->getHandlers($this->getNode()->childNodes);
    }

    /**
     * @return mixed
     */
    public function getNodeValue()
    {
        $nodeValue = trim($this->getNode()->nodeValue);
        $nodeValue = str_replace([
            "\r",
            "\n",
            "\t",
        ], [
            '',
            '',
            ' ',
        ], $nodeValue);

        return preg_replace('[\s+]', ' ', $nodeValue);
    }

    /**
     * Alias for AbstractNodeHandler::getNodeValue().
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->getNodeValue();
    }

    public function getValueNamespace(): ?string
    {
        return null;
    }

    /**
     * @param null|Traversable<DOMNode> $nodes
     *
     * @return AbstractAttributeHandler[]|AbstractElementHandler[]|AbstractNodeHandler[]
     */
    private function getHandlers(?Traversable $nodes): array
    {
        if (is_null($nodes)) {
            return [];
        }

        $handlers = [];
        $index = 0;
        foreach ($nodes as $node) {
            $handlers[] = $this->getDomDocumentHandler()->getHandler($node, $index++);
        }

        return $handlers;
    }
}
