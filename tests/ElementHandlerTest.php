<?php

declare(strict_types=1);

namespace WsdlToPhp\DomHandler\Tests;

use WsdlToPhp\DomHandler\AbstractAttributeHandler;
use WsdlToPhp\DomHandler\AbstractElementHandler;
use WsdlToPhp\DomHandler\AttributeHandler;
use WsdlToPhp\DomHandler\ElementHandler;

/**
 * @internal
 *
 * @coversDefaultClass \WsdlToPhp\DomHandler\ElementHandler
 */
final class ElementHandlerTest extends TestCase
{
    public function testHasAttribute(): void
    {
        $domDocument = DomDocumentHandlerTest::bingInstance();

        // first element tag
        $element = $domDocument->getElementByName('element');
        // first schema tag
        $schema = $domDocument->getElementByName('schema');

        $this->assertTrue($element->hasAttribute('minOccurs'));
        $this->assertTrue($element->hasAttribute('type'));
        $this->assertFalse($element->hasAttribute('minoccurs'));
        $this->assertTrue($schema->hasAttribute('targetNamespace'));
        $this->assertFalse($schema->hasAttribute('targetnamespace'));
    }

    public function testGetAttribute(): void
    {
        $domDocument = DomDocumentHandlerTest::bingInstance();

        // first element tag
        $element = $domDocument->getElementByName('element');
        // first schema tag
        $schema = $domDocument->getElementByName('schema');

        $this->assertInstanceOf(AttributeHandler::class, $schema->getAttribute('elementFormDefault'));
        $this->assertEmpty($schema->getAttribute('targetnamespace'));
        $this->assertInstanceOf(AttributeHandler::class, $element->getAttribute('name'));
        $this->assertEmpty($schema->getAttribute('foo'));
    }

    public function testGetElementChildren(): void
    {
        $domDocument = DomDocumentHandlerTest::bingInstance();

        // first schema tag
        $schema = $domDocument->getElementByName('schema');
        // first element tag
        $element = $domDocument->getElementByName('element');

        $this->assertNotEmpty($schema->getElementChildren());
        $this->assertContainsOnlyInstancesOf(AbstractElementHandler::class, $schema->getElementChildren());
        $this->assertEmpty($element->getElementChildren());
    }

    public function testGetChildrenByName(): void
    {
        $domDocument = DomDocumentHandlerTest::bingInstance();

        // first sequence tag
        $sequence = $domDocument->getElementByName('sequence');

        $childrenByName = $sequence->getChildrenByName('element');
        foreach ($childrenByName as $child) {
            $this->assertSame('element', $child->getName());
        }
    }

    public function testGetChildByNameAndAttributes(): void
    {
        $domDocument = DomDocumentHandlerTest::bingInstance();

        // first message tag
        $message = $domDocument->getElementByName('message');
        $part = $message->getChildByNameAndAttributes('part', [
            'name' => 'parameters',
            'element' => 'tns:SearchRequest',
        ]);

        $this->assertInstanceOf(ElementHandler::class, $part);
        $this->assertSame('parameters', $part->getAttributeValue('name'));
        $this->assertSame('SearchRequest', $part->getAttributeValue('element'));
    }

    public function testGetMaxOccursUnbounded(): void
    {
        $domDocument = DomDocumentHandlerTest::yandexDirectApiAdGroupsInstance();

        $element = $domDocument->getElementByNameAndAttributes('element', [
            'name' => 'CampaignIds',
        ]);

        $this->assertSame('unbounded', $element->getMaxOccurs());
    }

    public function testGetMaxOccursOne(): void
    {
        $domDocument = DomDocumentHandlerTest::yandexDirectApiAdGroupsInstance();

        $element = $domDocument->getElementByNameAndAttributes('element', [
            'name' => 'NegativeKeywords',
        ]);

        $this->assertSame(AbstractAttributeHandler::DEFAULT_OCCURRENCE_VALUE, $element->getMaxOccurs());
    }

    public function testGetMinOccursNone(): void
    {
        $domDocument = DomDocumentHandlerTest::yandexDirectApiAdGroupsInstance();

        $element = $domDocument->getElementByNameAndAttributes('element', [
            'name' => 'NegativeKeywords',
        ]);

        $this->assertSame(0, $element->getMinOccurs());
    }

    public function testGetMinOccursOne(): void
    {
        $domDocument = DomDocumentHandlerTest::yandexDirectApiAdGroupsInstance();

        $element = $domDocument->getElementByNameAndAttributes('element', [
            'name' => 'Name',
        ]);

        $this->assertSame(1, $element->getMinOccurs());
    }

    public function testCanOccurSeveralTimes(): void
    {
        $domDocument = DomDocumentHandlerTest::yandexDirectApiAdGroupsInstance();

        $element = $domDocument->getElementByNameAndAttributes('element', [
            'name' => 'RegionIds',
        ]);

        $this->assertTrue($element->canOccurSeveralTimes());
    }

    public function testCanOccurOnlyOnce(): void
    {
        $domDocument = DomDocumentHandlerTest::yandexDirectApiAdGroupsInstance();

        /** @var AbstractElementHandler $element */
        $element = $domDocument->getElementByNameAndAttributes('element', [
            'name' => 'CampaignId',
        ]);

        $this->assertTrue($element->canOccurOnlyOnce());
    }

    public function testCanOccurOnlyOnceEvenForOptionalElement(): void
    {
        $domDocument = DomDocumentHandlerTest::yandexDirectApiAdGroupsInstance();

        $element = $domDocument->getElementByNameAndAttributes('element', [
            'name' => 'NegativeKeywords',
        ]);

        $this->assertTrue($element->canOccurOnlyOnce());
    }

    public function testIsOptional(): void
    {
        $domDocument = DomDocumentHandlerTest::yandexDirectApiAdGroupsInstance();

        $element = $domDocument->getElementByNameAndAttributes('element', [
            'name' => 'NegativeKeywords',
        ]);

        $this->assertTrue($element->isOptional());
    }

    public function testIsRequired(): void
    {
        $domDocument = DomDocumentHandlerTest::yandexDirectApiAdGroupsInstance();

        $element = $domDocument->getElementByNameAndAttributes('element', [
            'name' => 'Name',
        ]);

        $this->assertTrue($element->isRequired());
    }

    public function testIsNotRequired(): void
    {
        $domDocument = DomDocumentHandlerTest::yandexDirectApiAdGroupsInstance();

        $element = $domDocument->getElementByNameAndAttributes('element', [
            'name' => 'NegativeKeywords',
        ]);

        $this->assertFalse($element->isRequired());
    }

    public function testYandexGetNillableTrue(): void
    {
        $domDocument = DomDocumentHandlerTest::yandexDirectApiAdGroupsInstance();

        $element = $domDocument->getElementByNameAndAttributes('element', [
            'name' => 'NegativeKeywords',
        ]);

        $this->assertTrue($element->getNillable());
    }

    public function testYandexIsRemovableTrue(): void
    {
        $domDocument = DomDocumentHandlerTest::yandexDirectApiAdGroupsInstance();

        $element = $domDocument->getElementByNameAndAttributes('element', [
            'name' => 'NegativeKeywords',
        ]);

        $this->assertTrue($element->isRemovable());
    }

    public function testYandexGetNillableFalse(): void
    {
        $domDocument = DomDocumentHandlerTest::yandexDirectApiAdGroupsInstance();

        $element = $domDocument->getElementByNameAndAttributes('element', [
            'name' => 'TrackingParams',
        ]);

        $this->assertFalse($element->getNillable());
    }

    public function testYandexIsRemovableFalse(): void
    {
        $domDocument = DomDocumentHandlerTest::yandexDirectApiAdGroupsInstance();

        $element = $domDocument->getElementByNameAndAttributes('element', [
            'name' => 'TrackingParams',
        ]);

        $this->assertFalse($element->isRemovable());
    }

    public function testActonGetNillableFalse(): void
    {
        $domDocument = DomDocumentHandlerTest::actonInstance();

        $element = $domDocument->getElementByNameAndAttributes('element', [
            'name' => 'email',
        ]);

        $this->assertFalse($element->getNillable());
    }

    public function testActonIsRemovableFalse(): void
    {
        $domDocument = DomDocumentHandlerTest::actonInstance();

        $element = $domDocument->getElementByNameAndAttributes('element', [
            'name' => 'email',
        ]);

        $this->assertFalse($element->isRemovable());
    }

    public function testActonGetNillableTrue(): void
    {
        $domDocument = DomDocumentHandlerTest::actonInstance();

        $element = $domDocument->getElementByNameAndAttributes('element', [
            'name' => 'serverUrl',
        ]);

        $this->assertTrue($element->getNillable());
    }

    public function testActonGetNillableTrueIsRemovableFalse(): void
    {
        $domDocument = DomDocumentHandlerTest::actonInstance();

        $element = $domDocument->getElementByNameAndAttributes('element', [
            'name' => 'serverUrl',
        ]);

        $this->assertFalse($element->isRemovable());
    }
}
