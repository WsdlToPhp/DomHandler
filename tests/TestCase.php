<?php

declare(strict_types=1);

namespace WsdlToPhp\DomHandler\Tests;

use PHPUnit\Framework\TestCase as PHPUnitFrameworkTestCase;

abstract class TestCase extends PHPUnitFrameworkTestCase
{
    public static function wsdlBingPath(): string
    {
        return __DIR__ . '/resources/bingsearch.wsdl';
    }

    public static function onlineWsdlBingPath(): string
    {
        return 'http://api.search.live.net/search.wsdl';
    }

    public static function wsdlActonPath(): string
    {
        return __DIR__ . '/resources/ActonService2.local.wsdl';
    }

    public static function wsdlYandexDirectApiCampaignsPath(): string
    {
        return __DIR__ . '/resources/directapi/campaigns.wsdl';
    }

    public static function wsdlYandexDirectApiAdGroupsPath(): string
    {
        return __DIR__ . '/resources/directapi/adgroups.wsdl';
    }

    public static function wsdlYandexDirectApiGeneralPath(): string
    {
        return __DIR__ . '/resources/directapi/general.xsd';
    }

    public static function wsdlYandexDirectApiLivePath(): string
    {
        return __DIR__ . '/resources/directapi/live.wsdl';
    }

    public static function wsdlEmptyPath(): string
    {
        return __DIR__ . '/resources/empty.wsdl';
    }
}
