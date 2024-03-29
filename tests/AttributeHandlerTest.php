<?php

declare(strict_types=1);

namespace WsdlToPhp\DomHandler\Tests;

use WsdlToPhp\DomHandler\AbstractAttributeHandler;

/**
 * @internal
 *
 * @coversDefaultClass \WsdlToPhp\DomHandler\AttributeHandler
 */
final class AttributeHandlerTest extends TestCase
{
    public function testGetName(): void
    {
        $domDocument = DomDocumentHandlerTest::bingInstance();

        // first element tag
        $element = $domDocument->getElementByName('element');

        $this->assertEquals(AbstractAttributeHandler::ATTRIBUTE_MIN_OCCURS, $element->getAttribute(AbstractAttributeHandler::ATTRIBUTE_MIN_OCCURS)->getName());
        $this->assertEquals(AbstractAttributeHandler::ATTRIBUTE_MAX_OCCURS, $element->getAttribute(AbstractAttributeHandler::ATTRIBUTE_MAX_OCCURS)->getName());
        $this->assertEquals('name', $element->getAttribute('name')->getName());
        $this->assertEquals('default', $element->getAttribute('default')->getName());
    }

    public function testGetValue(): void
    {
        $domDocument = DomDocumentHandlerTest::bingInstance();

        // first element tag
        $element = $domDocument->getElementByName('element');

        $this->assertSame('0', $element->getAttributeValue(AbstractAttributeHandler::ATTRIBUTE_MIN_OCCURS));
        $this->assertSame('1', $element->getAttributeValue(AbstractAttributeHandler::ATTRIBUTE_MAX_OCCURS));
        $this->assertSame('Version', $element->getAttributeValue('name'));
        $this->assertSame('2.2', $element->getAttributeValue('default'));
        $this->assertSame('2.2', $element->getAttributeValue('default', false, true, null));
        $this->assertSame(2, $element->getAttributeValue('default', false, true, 'int'));
        $this->assertSame(2.2, $element->getAttributeValue('default', false, true, 'float'));
    }

    public function testGetValueNamespace(): void
    {
        $domDocument = DomDocumentHandlerTest::bingInstance();

        $sequence = $domDocument->getElementByName('sequence');
        $elements = $sequence->getChildrenByName('element');

        $namespaces = [
            'xsd',
            'xsd',
            'xsd',
            'xsd',
            'xsd',
            'tns',
            'xsd',
            'xsd',
            'xsd',
            'tns',
            'tns',
            'tns',
            'tns',
            'tns',
            'tns',
            'tns',
            'tns',
            'tns',
        ];

        foreach ($elements as $index => $element) {
            $this->assertSame($namespaces[$index], $element->getAttribute('type')->getValueNamespace());
        }
    }

    public function testGetNamespaceNull(): void
    {
        $domDocument = DomDocumentHandlerTest::bingInstance();

        // first element tag
        $element = $domDocument->getElementByName('element');

        $this->assertNull($element->getAttribute(AbstractAttributeHandler::ATTRIBUTE_MIN_OCCURS)->getNamespace());
    }

    public function testGetMaxOccurs(): void
    {
        $domDocument = DomDocumentHandlerTest::yandexDirectApiAdGroupsInstance();

        $element = $domDocument->getElementByName('element');

        $this->assertEquals(AbstractAttributeHandler::VALUE_UNBOUNDED, $element->getAttributeValue(AbstractAttributeHandler::ATTRIBUTE_MAX_OCCURS));
        $this->assertEquals(0, $element->getAttributeValue(AbstractAttributeHandler::ATTRIBUTE_MIN_OCCURS));
    }
}
