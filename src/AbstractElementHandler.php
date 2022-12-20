<?php

declare(strict_types=1);

namespace WsdlToPhp\DomHandler;

abstract class AbstractElementHandler extends AbstractNodeHandler
{
    public function __construct(\DOMElement $element, AbstractDomDocumentHandler $domDocument, int $index = -1)
    {
        parent::__construct($element, $domDocument, $index);
    }

    public function getElement(): ?\DOMElement
    {
        return $this->getNode() instanceof \DOMElement ? $this->getNode() : null;
    }

    public function hasAttribute(string $name): bool
    {
        return $this->getElement() && $this->getElement()->hasAttribute($name);
    }

    public function getAttribute(string $name): ?AttributeHandler
    {
        if (!$this->hasAttribute($name) || !$this->getElement()) {
            return null;
        }

        $attribute = $this->getDomDocumentHandler()->getHandler($this->getElement()->getAttributeNode($name));

        return $attribute instanceof AttributeHandler ? $attribute : null;
    }

    /**
     * @return null|bool|mixed|string
     */
    public function getAttributeValue(string $name, bool $withNamespace = false, bool $withinItsType = true, ?string $asType = AbstractAttributeHandler::DEFAULT_VALUE_TYPE)
    {
        $value = null;
        $attribute = $this->getAttribute($name);
        if ($attribute instanceof AbstractAttributeHandler) {
            $value = $attribute->getValue($withNamespace, $withinItsType, $asType);
        }

        return $value;
    }

    /**
     * @return array<int, AbstractElementHandler|AbstractNodeHandler>
     */
    public function getChildrenByName(string $name): array
    {
        $children = [];

        if (!$this->hasChildren() || !$this->getElement()) {
            return $children;
        }

        foreach ($this->getElement()->getElementsByTagName($name) as $index => $node) {
            $children[] = $this->getDomDocumentHandler()->getHandler($node, $index);
        }

        return $children;
    }

    /**
     * @return AbstractElementHandler[]
     */
    public function getElementChildren(): array
    {
        return $this->hasChildren() ? $this->getDomDocumentHandler()->getElementsHandlers($this->getChildNodes()) : [];
    }

    /**
     * @param string[] $attributes
     *
     * @return AbstractAttributeHandler[]|AbstractElementHandler[]|AbstractNodeHandler[]
     */
    public function getChildrenByNameAndAttributes(string $name, array $attributes): array
    {
        return $this->getDomDocumentHandler()->getElementsByNameAndAttributes($name, $attributes, $this->getNode());
    }

    /**
     * @param string[] $attributes
     */
    public function getChildByNameAndAttributes(string $name, array $attributes): ?ElementHandler
    {
        $children = $this->getChildrenByNameAndAttributes($name, $attributes);

        $child = array_shift($children);

        return $child instanceof ElementHandler ? $child : null;
    }

    /**
     * Info at {@link https://www.w3.org/TR/xmlschema-0/#OccurrenceConstraints}.
     *
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
     * Info at {@link https://www.w3.org/TR/xmlschema-0/#OccurrenceConstraints}.
     *
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
     * Info at {@link http://www.w3schools.com/xml/el_element.asp}.
     */
    public function getNillable(): bool
    {
        return (bool) $this->getAttributeValue(AbstractAttributeHandler::ATTRIBUTE_NILLABLE, false, true, 'bool');
    }

    /**
     * Info at {@link https://www.w3.org/TR/xmlschema-0/#OccurrenceConstraints}.
     */
    public function canOccurSeveralTimes(): bool
    {
        return (1 < $this->getMinOccurs()) || (1 < $this->getMaxOccurs()) || (AbstractAttributeHandler::VALUE_UNBOUNDED === $this->getMaxOccurs());
    }

    /**
     * Info at {@link https://www.w3.org/TR/xmlschema-0/#OccurrenceConstraints}.
     */
    public function canOccurOnlyOnce(): bool
    {
        return 1 === $this->getMaxOccurs();
    }

    /**
     * Info at {@link https://www.w3.org/TR/xmlschema-0/#OccurrenceConstraints}.
     */
    public function isOptional(): bool
    {
        return 0 === $this->getMinOccurs();
    }

    /**
     * Info at {@link https://www.w3.org/TR/xmlschema-0/#OccurrenceConstraints}.
     */
    public function isRequired(): bool
    {
        return 1 <= $this->getMinOccurs();
    }

    public function isRemovable(): bool
    {
        return $this->isOptional() && $this->getNillable();
    }
}
