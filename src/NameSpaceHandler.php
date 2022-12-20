<?php

declare(strict_types=1);

namespace WsdlToPhp\DomHandler;

class NameSpaceHandler extends AttributeHandler
{
    protected \DOMNameSpaceNode $nodeNameSpace;

    public function __construct(\DOMNameSpaceNode $nameSpaceNode, AbstractDomDocumentHandler $domDocumentHandler, int $index = -1)
    {
        parent::__construct(new \DOMAttr($nameSpaceNode->nodeName, $nameSpaceNode->nodeValue), $domDocumentHandler, $index);
        $this->nodeNameSpace = $nameSpaceNode;
    }

    public function getValue(bool $withNamespace = false, bool $withinItsType = true, ?string $asType = self::DEFAULT_VALUE_TYPE)
    {
        return parent::getValue(true, $withinItsType, $asType);
    }

    public function getValueNamespace(): ?string
    {
        return null;
    }
}
