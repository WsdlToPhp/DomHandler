<?php

declare(strict_types=1);

namespace WsdlToPhp\DomHandler;

use DOMAttr;

class AbstractAttributeHandler extends AbstractNodeHandler
{
    const DEFAULT_VALUE_TYPE = 'string';

    const ATTRIBUTE_NAMESPACE = 'namespace';

    const ATTRIBUTE_NAME = 'name';

    const ATTRIBUTE_REF = 'ref';

    const ATTRIBUTE_VALUE = 'value';

    const ATTRIBUTE_TYPE = 'type';

    const ATTRIBUTE_ABSTRACT = 'abstract';

    const ATTRIBUTE_MAX_OCCURS = 'maxOccurs';

    const ATTRIBUTE_MIN_OCCURS = 'minOccurs';

    const ATTRIBUTE_NILLABLE = 'nillable';

    const VALUE_UNBOUNDED = 'unbounded';

    /**
     * @deprecated
     */
    const DEFAULT_OCCURENCE_VALUE = 1;

    const DEFAULT_OCCURRENCE_VALUE = 1;

    public function getAttribute(): DOMAttr
    {
        return $this->getNode();
    }
    /**
     * Tries to get attribute type on the same node in order to return the value of the attribute in its type
     * @return string|null
     */
    public function getType(): ?string
    {
        $type = null;
        if (($parent = $this->getParent()) instanceof ElementHandler && $parent->hasAttribute(self::ATTRIBUTE_TYPE)) {
            $type = $parent->getAttribute(self::ATTRIBUTE_TYPE)->getValue(false, false);
        }

        return $type;
    }

    public function getValue(bool $withNamespace = false, bool $withinItsType = true, ?string $asType = self::DEFAULT_VALUE_TYPE)
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

    public function getValueNamespace(): ?string
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
     * @param string|null $knownType the value
     * @return mixed
     */
    public static function getValueWithinItsType($value, ?string $knownType = null)
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
