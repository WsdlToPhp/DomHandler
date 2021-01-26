<?php

declare(strict_types=1);

namespace WsdlToPhp\DomHandler;

use DOMElement;

abstract class AbstractElementHandler extends AbstractNodeHandler
{
    public function __construct(DOMElement $element, AbstractDomDocumentHandler $domDocument, int $index = -1)
    {
        parent::__construct($element, $domDocument, $index);
    }

    public function getNode(): DOMElement
    {
        return parent::getNode();
    }

    public function getElement(): DOMElement
    {
        return $this->getNode();
    }

    public function hasAttribute(string $name): bool
    {
        return $this->getElement()->hasAttribute($name);
    }

    public function getAttribute(string $name): ?AttributeHandler
    {
        return $this->hasAttribute($name) ? $this->getDomDocumentHandler()->getHandler($this->getNode()->getAttributeNode($name)) : null;
    }

    public function getAttributeValue(string $name, bool $withNamespace = false, bool $withinItsType = true, ?string $asType = AbstractAttributeHandler::DEFAULT_VALUE_TYPE)
    {
        $value = null;
        $attribute = $this->getAttribute($name);
        if ($attribute instanceof AbstractAttributeHandler) {
            $value = $attribute->getValue($withNamespace, $withinItsType, $asType);
        }

        return $value;
    }

    public function getChildrenByName(string $name): array
    {
        $children = array();
        if ($this->hasChildren()) {
            foreach ($this->getElement()->getElementsByTagName($name) as $index => $node) {
                $children[] = $this->getDomDocumentHandler()->getHandler($node, $index);
            }
        }

        return $children;
    }

    public function getElementChildren(): array
    {
        $children = array();
        if ($this->hasChildren()) {
            $children = $this->getDomDocumentHandler()->getElementsHandlers($this->getChildNodes());
        }

        return $children;
    }

    public function getChildrenByNameAndAttributes(string $name, array $attributes): array
    {
        return $this->getDomDocumentHandler()->getElementsByNameAndAttributes($name, $attributes, $this->getNode());
    }

    public function getChildByNameAndAttributes(string $name, array $attributes): ?ElementHandler
    {
        $children = $this->getChildrenByNameAndAttributes($name, $attributes);

        return empty($children) ? null : array_shift($children);
    }

    /**
     * Info at {@link https://www.w3.org/TR/xmlschema-0/#OccurrenceConstraints}
     * @return mixed
     */
    public function getMaxOccurs()
    {
        $maxOccurs = $this->getAttributeValue(AbstractAttributeHandler::ATTRIBUTE_MAX_OCCURS);
        if (AbstractAttributeHandler::VALUE_UNBOUNDED === $maxOccurs) {
            return $maxOccurs;
        }
        if (!is_numeric($maxOccurs)) {
            return AbstractAttributeHandler::DEFAULT_OCCURRENCE_VALUE;
        }

        return (int) $maxOccurs;
    }

    /**
     * Info at {@link https://www.w3.org/TR/xmlschema-0/#OccurrenceConstraints}
     * @return int
     */
    public function getMinOccurs()
    {
        $minOccurs = $this->getAttributeValue(AbstractAttributeHandler::ATTRIBUTE_MIN_OCCURS);
        if (!is_numeric($minOccurs)) {
            return AbstractAttributeHandler::DEFAULT_OCCURRENCE_VALUE;
        }

        return (int) $minOccurs;
    }

    /**
     * Info at {@link http://www.w3schools.com/xml/el_element.asp}
     * @return bool
     */
    public function getNillable(): bool
    {
        return (bool) $this->getAttributeValue(AbstractAttributeHandler::ATTRIBUTE_NILLABLE, false, true, 'bool');
    }

    /**
     * Info at {@link https://www.w3.org/TR/xmlschema-0/#OccurrenceConstraints}
     * @return bool
     */
    public function canOccurSeveralTimes(): bool
    {
        return (1 < $this->getMinOccurs()) || (1 < $this->getMaxOccurs()) || (AbstractAttributeHandler::VALUE_UNBOUNDED === $this->getMaxOccurs());
    }

    /**
     * Info at {@link https://www.w3.org/TR/xmlschema-0/#OccurrenceConstraints}
     * @return bool
     */
    public function canOccurOnlyOnce(): bool
    {
        return 1 === $this->getMaxOccurs();
    }

    /**
     * Info at {@link https://www.w3.org/TR/xmlschema-0/#OccurrenceConstraints}
     * @return bool
     */
    public function isOptional(): bool
    {
        return 0 === $this->getMinOccurs();
    }

    /**
     * Info at {@link https://www.w3.org/TR/xmlschema-0/#OccurrenceConstraints}
     * @return bool
     */
    public function isRequired(): bool
    {
        return 1 <= $this->getMinOccurs();
    }

    /**
     * @return bool
     */
    public function isRemovable(): bool
    {
        return $this->isOptional() && $this->getNillable();
    }
}
