<?php

declare(strict_types=1);

namespace WsdlToPhp\DomHandler\Tests;

use DOMDocument;
use InvalidArgumentException;
use WsdlToPhp\DomHandler\DomDocumentHandler;
use WsdlToPhp\DomHandler\ElementHandler;
use WsdlToPhp\DomHandler\NodeHandler;

/**
 * @internal
 * @coversNothing
 */
class DomDocumentHandlerTest extends TestCase
{
    protected static ?DomDocumentHandler $actonInstance;
    protected static ?DomDocumentHandler $ebayInstance;
    protected static ?DomDocumentHandler $bingInstance;
    protected static ?DomDocumentHandler $emptyInstance;
    protected static ?DomDocumentHandler $yandexDirectApiAdGroupsInstance;
    protected static ?DomDocumentHandler $yandexDirectApiGeneralInstance;

    public static function actonInstance(): DomDocumentHandler
    {
        if (!isset(self::$actonInstance)) {
            $doc = new DOMDocument('1.0', 'utf-8');
            $doc->load(self::wsdlActonPath());
            self::$actonInstance = new DomDocumentHandler($doc);
        }

        return self::$actonInstance;
    }

    public static function bingInstance(): DomDocumentHandler
    {
        if (!isset(self::$bingInstance)) {
            $doc = new DOMDocument('1.0', 'utf-8');
            $doc->load(self::wsdlBingPath());
            self::$bingInstance = new DomDocumentHandler($doc);
        }

        return self::$bingInstance;
    }

    public static function emptyInstance(): DomDocumentHandler
    {
        if (!isset(self::$emptyInstance)) {
            $doc = new DOMDocument('1.0', 'utf-8');
            @$doc->load(self::wsdlEmptyPath());
            self::$emptyInstance = new DomDocumentHandler($doc);
        }

        return self::$emptyInstance;
    }

    public static function yandexDirectApiAdGroupsInstance(): DomDocumentHandler
    {
        if (!isset(self::$yandexDirectApiAdGroupsInstance)) {
            $doc = new DOMDocument('1.0', 'utf-8');
            $doc->load(self::wsdlYandexDirectApiAdGroupsPath());
            self::$yandexDirectApiAdGroupsInstance = new DomDocumentHandler($doc);
        }

        return self::$yandexDirectApiAdGroupsInstance;
    }

    public static function yandexDirectApiGeneralInstance(): DomDocumentHandler
    {
        if (!isset(self::$yandexDirectApiGeneralInstance)) {
            $doc = new DOMDocument('1.0', 'utf-8');
            $doc->load(self::wsdlYandexDirectApiGeneralPath());
            self::$yandexDirectApiGeneralInstance = new DomDocumentHandler($doc);
        }

        return self::$yandexDirectApiGeneralInstance;
    }

    public function testGetNodeByName()
    {
        $instance = self::bingInstance();

        $this->assertInstanceOf(NodeHandler::class, $instance->getNodeByName('types'));
        $this->assertInstanceOf(NodeHandler::class, $instance->getNodeByName('definitions'));
        $this->assertNull($instance->getNodeByName('foo'));
    }

    public function testGetNodesByName()
    {
        $instance = self::bingInstance();

        $this->assertNotEmpty($instance->getNodesByName('element'));
        $this->assertEmpty($instance->getNodesByName('foo'));
    }

    public function testGetElementsByName()
    {
        $instance = self::bingInstance();

        $this->assertNotEmpty($instance->getElementsByName('element'));
        $this->assertContainsOnlyInstancesOf(ElementHandler::class, $instance->getElementsByName('element'));
        $this->assertEmpty($instance->getElementsByName('foo'));
    }

    public function testGetElementByNameIsNull()
    {
        $instance = self::bingInstance();

        $this->assertNull($instance->getElementByName('foo'));
    }

    public function testGetElementsByNameAndAttributes()
    {
        $instance = self::bingInstance();

        $parts = $instance->getElementsByNameAndAttributes('part', [
            'name' => 'parameters',
            'element' => 'tns:SearchRequest',
        ]);
        $this->assertNotEmpty($parts);
        $this->assertContainsOnlyInstancesOf(ElementHandler::class, $parts);
    }

    public function testGetElementsByNameAndAttributesFromDomNode()
    {
        $instance = self::yandexDirectApiAdGroupsInstance();
        $xsd = self::yandexDirectApiGeneralInstance();

        $elements = $instance->getElementsByNameAndAttributes('element', [
            'minOccurs' => 1,
            'maxOccurs' => 1,
        ], $xsd->getElementByNameAndAttributes('complexType', [
            'name' => 'ExceptionNotification',
        ])->getNode());
        $this->assertCount(2, $elements);
        $this->assertContainsOnlyInstancesOf(ElementHandler::class, $elements);
    }

    public function testGetElementByNameAndAttributes()
    {
        $instance = self::bingInstance();

        $part = $instance->getElementByNameAndAttributes('part', [
            'name' => 'parameters',
            'element' => 'tns:SearchRequest',
        ]);
        $this->assertInstanceOf(ElementHandler::class, $part);
    }

    public function testGetElementByNameAndAttributesContainingString()
    {
        $instance = self::bingInstance();

        $part = $instance->getElementByNameAndAttributes('part', [
            'name' => 'parameters',
            'element' => '*:SearchRequest',
        ]);
        $this->assertInstanceOf(ElementHandler::class, $part);
    }

    public function testInitRootElementWithException()
    {
        $this->expectException(InvalidArgumentException::class);

        self::emptyInstance();
    }
}
