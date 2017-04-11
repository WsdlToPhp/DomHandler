<?php

namespace WsdlToPhp\DomHandler;

use WsdlToPhp\DomHandler\Tests\TestCase;
use WsdlToPhp\DomHandler\Tests\DomHandler\DomDocumentHandlerTest;

class NameSpaceHandlerTest extends TestCase
{
    /**
     * @see \WsdlToPhp\DomHandler\AbstractNodeHandler::getParent()
     * @return AbstractNodeHandler
     */
    public function testGetParent()
    {
        $domDocument = DomDocumentHandlerTest::bingInstance();

        $nameSpaceHandler = $domDocument->getRootElement()->getAttribute('xmlns:xsi');

        $this->assertNull($nameSpaceHandler->getParent());
    }
    /**
     * @return null|string
     */
    public function testGetValueNamespace()
    {
        $domDocument = DomDocumentHandlerTest::bingInstance();

        $this->assertNull($domDocument->getRootElement()->getAttribute('xmlns:xsi')->getValueNamespace());
    }
}
