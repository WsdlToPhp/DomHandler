<?php

declare(strict_types=1);

namespace WsdlToPhp\DomHandler;

use DOMAttr;
use DOMElement;
use DOMNode;

class DomDocumentHandler extends AbstractDomDocumentHandler
{
    public function getRootElement(): ?ElementHandler
    {
        return $this->rootElement;
    }

    protected function getNodeHandler(DOMNode $node, AbstractDomDocumentHandler $domDocument, int $index = -1): NodeHandler
    {
        return new NodeHandler($node, $domDocument, $index);
    }

    protected function getElementHandler(DOMElement $element, AbstractDomDocumentHandler $domDocument, int $index = -1): ElementHandler
    {
        return new ElementHandler($element, $domDocument, $index);
    }

    protected function getAttributeHandler(DOMAttr $attribute, AbstractDomDocumentHandler $domDocument, int $index = -1): AttributeHandler
    {
        return new AttributeHandler($attribute, $domDocument, $index);
    }
}
