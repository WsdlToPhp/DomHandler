<?php

declare(strict_types=1);

namespace WsdlToPhp\DomHandler;

class AbstractAttributeHandler extends AbstractNodeHandler
{
    public const DEFAULT_VALUE_TYPE = 'string';

    public const ATTRIBUTE_NAMESPACE = 'namespace';

    public const ATTRIBUTE_NAME = 'name';

    public const ATTRIBUTE_REF = 'ref';

    public const ATTRIBUTE_VALUE = 'value';

    public const ATTRIBUTE_TYPE = 'type';

    public const ATTRIBUTE_ABSTRACT = 'abstract';

    public const ATTRIBUTE_MAX_OCCURS = 'maxOccurs';

    public const ATTRIBUTE_MIN_OCCURS = 'minOccurs';

    public const ATTRIBUTE_NILLABLE = 'nillable';

    public const VALUE_UNBOUNDED = 'unbounded';

    /**
     * @deprecated
     */
    public const DEFAULT_OCCURENCE_VALUE = 1;

    public const DEFAULT_OCCURRENCE_VALUE = 1;

    public function getAttribute(): ?\DOMAttr
    {
        return $this->getNode() instanceof \DOMAttr ? $this->getNode() : null;
    }

    /**
     * Tries to get attribute type on the same node in order to return the value of the attribute in its type.
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
        if (false === $withNamespace && !empty($value)) {
            $value = implode('', array_slice(explode(':', $value), -1, 1));
        }
        if (null !== $value && true === $withinItsType) {
            $value = self::getValueWithinItsType($value, empty($asType) ? $this->getType() : $asType);
        }

        return $value;
    }

    public function getValueNamespace(): ?string
    {
        $value = $this->getAttribute()->value;
        $namespace = null;
        if (false !== strpos($value, ':')) {
            $namespace = implode('', array_slice(explode(':', $value), 0, -1));
        }

        return $namespace;
    }

    /**
     * Returns the value with good type.
     *
     * @param null|bool|float|int|string $value     the value
     * @param null|string                $knownType the value expected type
     *
     * @return mixed
     */
    public static function getValueWithinItsType($value, ?string $knownType = null)
    {
        if (is_int($value) || (!is_null($value) && in_array($knownType, [
            'time',
            'positiveInteger',
            'unsignedLong',
            'unsignedInt',
            'short',
            'long',
            'int',
            'integer',
        ], true))) {
            return (int) $value;
        }
        if (is_float($value) || (!is_null($value) && in_array($knownType, [
            'float',
            'double',
            'decimal',
        ], true))) {
            return (float) $value;
        }
        if (is_bool($value) || (!is_null($value) && in_array($knownType, [
            'bool',
            'boolean',
        ], true))) {
            return 'true' === $value || true === $value || '1' === $value;
        }

        return $value;
    }
}
