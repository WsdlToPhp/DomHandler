<?php

namespace WsdlToPhp\DomHandler;

class AbstractAttributeHandler extends AbstractNodeHandler
{
    /**
     * @var string
     */
    const DEFAULT_VALUE_TYPE = 'string';
    /**
     * @var string
     */
    const ATTRIBUTE_NAMESPACE = 'namespace';
    /**
     * @var string
     */
    const ATTRIBUTE_NAME = 'name';
    /**
     * @var string
     */
    const ATTRIBUTE_REF = 'ref';
    /**
     * @var string
     */
    const ATTRIBUTE_VALUE = 'value';
    /**
     * @var string
     */
    const ATTRIBUTE_TYPE = 'type';
    /**
     * @var string
     */
    const ATTRIBUTE_ABSTRACT = 'abstract';
    /**
     * @var string
     */
    const ATTRIBUTE_MAX_OCCURS = 'maxOccurs';
    /**
     * @var string
     */
    const ATTRIBUTE_MIN_OCCURS = 'minOccurs';
    /**
     * @var string
     */
    const ATTRIBUTE_NILLABLE = 'nillable';
    /**
     * @var string
     */
    const VALUE_UNBOUNDED = 'unbounded';
    /**
     * @var string
     */
    const DEFAULT_OCCURENCE_VALUE = 1;
    /**
     * @see \WsdlToPhp\DomHandler\AbstractNodeHandler::getNode()
     * @return \DOMAttr
     */
    public function getNode()
    {
        return parent::getNode();
    }
    /**
     * @return \DOMAttr
     */
    public function getAttribute()
    {
        return $this->getNode();
    }
    /**
     * Tries to get attribute type on the same node
     * in order to return the value of the attribute in its type
     * @return string|null
     */
    public function getType()
    {
        $type = null;
        if (($parent = $this->getParent()) instanceof ElementHandler && $parent->hasAttribute(self::ATTRIBUTE_TYPE)) {
            $type = $parent->getAttribute(self::ATTRIBUTE_TYPE)->getValue(false, false);
        }
        return $type;
    }
    /**
     * @param bool $withNamespace
     * @param bool $withinItsType
     * @param string $asType
     * @return mixed
     */
    public function getValue($withNamespace = false, $withinItsType = true, $asType = self::DEFAULT_VALUE_TYPE)
    {
        $value = $this->getAttribute()->value;
        if ($withNamespace === false && !empty($value)) {
            $value = implode('', array_slice(explode(':', $value), -1, 1));
        }
        if ($value !== null && $withinItsType === true) {
            $value = self::getValueWithinItsType($value, empty($asType) ? $this->getType() : $asType);
        }
        return $value;
    }
    /**
     * @return null|string
     */
    public function getValueNamespace()
    {
        $value = $this->getAttribute()->value;
        $namespace = null;
        if (strpos($value, ':') !== false) {
            $namespace = implode('', array_slice(explode(':', $value), 0, -1));
        }
        return $namespace;
    }

    /**
     * Returns the value with good type
     * @param mixed $value the value
     * @param string $knownType the value
     * @return mixed
     */
    public static function getValueWithinItsType($value, $knownType = null)
    {
        if (is_int($value) || (!is_null($value) && in_array($knownType, array(
            'time',
            'positiveInteger',
            'unsignedLong',
            'unsignedInt',
            'short',
            'long',
            'int',
            'integer',
        ), true))) {
            return intval($value);
        } elseif (is_float($value) || (!is_null($value) && in_array($knownType, array(
            'float',
            'double',
            'decimal',
        ), true))) {
            return floatval($value);
        } elseif (is_bool($value) || (!is_null($value) && in_array($knownType, array(
            'bool',
            'boolean',
        ), true))) {
            return ($value === 'true' || $value === true || $value === 1 || $value === '1');
        }
        return $value;
    }
}
