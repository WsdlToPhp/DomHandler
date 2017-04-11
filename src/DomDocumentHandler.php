<?php

namespace WsdlToPhp\DomHandler;

use WsdlToPhp\DomHandler\AbstractDomDocumentHandler;

class DomDocumentHandler extends AbstractDomDocumentHandler
{
    /**
     * @param \DOMNode $node
     * @param \WsdlToPhp\DomHandler\AbstractDomDocumentHandler $domDocument
     * @param int $index
     * @return NodeHandler
     * @see \WsdlToPhp\DomHandler\AbstractDomDocumentHandler::getNodeHandler()
     */
    protected function getNodeHandler(\DOMNode $node, AbstractDomDocumentHandler $domDocument, $index = -1)
    {
        return new NodeHandler($node, $domDocument, $index);
    }
    /**
     * @param \DOMElement $element
     * @param \WsdlToPhp\DomHandler\AbstractDomDocumentHandler $domDocument
     * @param int $index
     * @return ElementHandler
     * @see \WsdlToPhp\DomHandler\AbstractDomDocumentHandler::getNodeHandler()
     */
    protected function getElementHandler(\DOMElement $element, AbstractDomDocumentHandler $domDocument, $index = -1)
    {
        return new ElementHandler($element, $domDocument, $index);
    }
    /**
     * @param \DOMAttr $attribute
     * @param \WsdlToPhp\DomHandler\AbstractDomDocumentHandler $domDocument
     * @param int $index
     * @return AttributeHandler
     * @see \WsdlToPhp\DomHandler\AbstractDomDocumentHandler::getAttributeHandler()
     */
    protected function getAttributeHandler(\DOMAttr $attribute, AbstractDomDocumentHandler $domDocument, $index = -1)
    {
        return new AttributeHandler($attribute, $domDocument, $index);
    }
    /**
     * @return ElementHandler
     */
    public function getRootElement()
    {
        return $this->rootElement;
    }
}
