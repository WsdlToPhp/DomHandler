<?php

declare(strict_types=1);

namespace WsdlToPhp\DomHandler\Tests;

/**
 * @internal
 * @coversDefaultClass
 */
final class NameSpaceHandlerTest extends TestCase
{
    public function testGetParent()
    {
        $domDocument = DomDocumentHandlerTest::bingInstance();

        $nameSpaceHandler = $domDocument->getRootElement()->getAttribute('xmlns:xsi');

        $this->assertNull($nameSpaceHandler->getParent());
    }

    public function testGetValueNamespace()
    {
        $domDocument = DomDocumentHandlerTest::bingInstance();

        $this->assertNull($domDocument->getRootElement()->getAttribute('xmlns:xsi')->getValueNamespace());
    }

    public function testGetValue()
    {
        $domDocument = DomDocumentHandlerTest::bingInstance();

        $this->assertSame('http://www.w3.org/2001/XMLSchema-instance', $domDocument->getRootElement()->getAttribute('xmlns:xsi')->getValue());
    }
}
