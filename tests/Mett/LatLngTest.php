<?php

use Mett\LatLng;

class LatLngTest extends PHPUnit_Framework_TestCase
{
    public function testLatLonFromString1()
    {
        $latLon = LatLng::latLonFromString("N43°38'19.39\" E70°38'21.23\"");

        $expected = 43.638719444444;

        self::assertEquals($expected, $latLon->latitude);

        $expected = 70.639230555556;
        self::assertEquals($expected, $latLon->longitude);
    }

    public function testLatLonFromString2()
    {
        $latLon = LatLng::latLonFromString("43°38'19.39\"N 70°38'21.23\"E");

        $expected = 43.638719444444;

        self::assertEquals($expected, $latLon->latitude);

        $expected = 70.639230555556;
        self::assertEquals($expected, $latLon->longitude);
    }

    public function testLatLonFromString3()
    {
        $latLon = LatLng::latLonFromString("S43°38'19.39\" W70°38'21.23\"");

        $expected = -43.638719444444;

        self::assertEquals($expected, $latLon->latitude);

        $expected = -70.639230555556;
        self::assertEquals($expected, $latLon->longitude);
    }

    public function testLatLonFromString4()
    {
        $latLon = LatLng::latLonFromString("43°38'19.39\"S 70°38'21.23\"W");

        $expected = -43.638719444444;

        self::assertEquals($expected, $latLon->latitude);

        $expected = -70.639230555556;
        self::assertEquals($expected, $latLon->longitude);
    }

    public function testLatLonFromString5()
    {
        $latLon = LatLng::latLonFromString("43°38'19.39\"S;70°38'21.23\"W");

        $expected = -43.638719444444;

        self::assertEquals($expected, $latLon->latitude);

        $expected = -70.639230555556;
        self::assertEquals($expected, $latLon->longitude);
    }

    public function testLatLonFromString6()
    {
        $latLon = LatLng::latLonFromString("43°38'19,39\"S 70°38'21,23\"W");

        $expected = -43.638719444444;

        self::assertEquals($expected, $latLon->latitude);

        $expected = -70.639230555556;
        self::assertEquals($expected, $latLon->longitude);
    }

    public function testLatLonFromString7()
    {
        $latLon = LatLng::latLonFromString("N43°38’19.39“ E70 38 21.23''");

        $expected = 43.638719444444;

        self::assertEquals($expected, $latLon->latitude);

        $expected = 70.639230555556;
        self::assertEquals($expected, $latLon->longitude);
    }

    public function testLatLonFromString8()
    {
        $latLon = LatLng::latLonFromString("43,638719444444;70,639230555556");

        $expected = 43.638719444444;

        self::assertEquals($expected, $latLon->latitude);

        $expected = 70.639230555556;
        self::assertEquals($expected, $latLon->longitude);
    }

    public function testLatLonFromString9()
    {
        $latLon = LatLng::latLonFromString("+43.638719444444;+70.639230555556");

        $expected = 43.638719444444;

        self::assertEquals($expected, $latLon->latitude);

        $expected = 70.639230555556;
        self::assertEquals($expected, $latLon->longitude);
    }

    public function testLatLonFromStringNotNiceButOk()
    {
        $latLon = LatLng::latLonFromString("-43,638719444444;+70,639230555556");

        $expected = -43.638719444444;

        self::assertEquals($expected, $latLon->latitude);

        $expected = 70.639230555556;
        self::assertEquals($expected, $latLon->longitude);
    }

    public function testLatLonFromString10()
    {
        $latLon = LatLng::latLonFromString("+43.638719444444; -70.639230555556");

        $expected = 43.638719444444;

        self::assertEquals($expected, $latLon->latitude);

        $expected = -70.639230555556;
        self::assertEquals($expected, $latLon->longitude);
    }

    public function testLatLonFromString11()
    {
        $latLon = LatLng::latLonFromString("+43.0;-70.639230555556");

        $expected = 43;

        self::assertEquals($expected, $latLon->latitude);

        $expected = -70.639230555556;
        self::assertEquals($expected, $latLon->longitude);
    }

    public function testLatLonFromString12()
    {
        $latLon = LatLng::latLonFromString("-43;-70.639230555556");

        $expected = -43.0;

        self::assertEquals($expected, $latLon->latitude);

        $expected = -70.639230555556;
        self::assertEquals($expected, $latLon->longitude);
    }

    public function testLatLonFromString13()
    {
        $latLon = LatLng::latLonFromString("43.1;-70");

        $expected = 43.1;

        self::assertEquals($expected, $latLon->latitude);

        $expected = -70.0;
        self::assertEquals($expected, $latLon->longitude);
    }

    public function testLatLonFromString14()
    {
        $latLon = LatLng::latLonFromString("-43;-70");

        $expected = -43.0;

        self::assertEquals($expected, $latLon->latitude);

        $expected = -70.0;
        self::assertEquals($expected, $latLon->longitude);
    }

    public function testLatLonFromString15()
    {
        $latLon = LatLng::latLonFromString("-43,-70");

        $expected = -43.0;

        self::assertEquals($expected, $latLon->latitude);

        $expected = -70.0;
        self::assertEquals($expected, $latLon->longitude);
    }

    /**
     * @expectedException \Exception
     */
    public function testLatLonFromStringWithException1()
    {
        $latLon = LatLng::latLonFromString("+43,638719444444, -70,639230555556");

        $expected = 43.638719444444;

        self::assertEquals($expected, $latLon->latitude);

        $expected = -70.639230555556;
        self::assertEquals($expected, $latLon->longitude);
    }

    /**
     * @expectedException \Exception
     */
    public function testLatLonFromStringWithException2()
    {
        $latLon = LatLng::latLonFromString("+43,638719444444, -70.639230555556");

        $expected = 43.638719444444;

        self::assertEquals($expected, $latLon->latitude);

        $expected = -70.639230555556;
        self::assertEquals($expected, $latLon->longitude);
    }

    /**
     * @expectedException \Exception
     */
    public function testLatLonFromStringWithException3()
    {
        $latLon = LatLng::latLonFromString("+43;638719444444,-70.639230555556");

        $expected = 43.638719444444;

        self::assertEquals($expected, $latLon->latitude);

        $expected = -70.639230555556;
        self::assertEquals($expected, $latLon->longitude);
    }
}