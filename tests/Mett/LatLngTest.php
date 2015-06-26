<?php

use Mett\LatLng;

class LatLngTest extends PHPUnit_Framework_TestCase
{
    protected $map;

    public function setUp()
    {
        $latitude  = 43.638719444444;
        $longitude = 70.639230555556;
        $this->map = [
             0 => ['source' =>  "N 43°38'19.39\" E 70°38'21.23\"",          'latitude' =>  $latitude, 'longitude' =>  $longitude],
             1 => ['source' =>  "N43°38'19.39\" E70°38'21.23\"",            'latitude' =>  $latitude, 'longitude' =>  $longitude],
             2 => ['source' =>  "S -43°38'19.39\" W -70°38'21.23\"",        'latitude' =>  $latitude, 'longitude' =>  $longitude],
             3 => ['source' =>  "S-43°38'19.39\" W-70°38'21.23\"",          'latitude' =>  $latitude, 'longitude' =>  $longitude],
             4 => ['source' =>  "N43°38.32316667' E70°38.35383333'",        'latitude' =>  $latitude, 'longitude' =>  $longitude],
             5 => ['source' =>  "S-43°38.32316667' W-70°38.35383333'",      'latitude' =>  $latitude, 'longitude' =>  $longitude],
             6 => ['source' =>  "S -43°38.32316667' W -70°38.35383333'",    'latitude' =>  $latitude, 'longitude' =>  $longitude],
             7 => ['source' =>  "43°38'19.39\"N 70°38'21.23\"E",            'latitude' =>  $latitude, 'longitude' =>  $longitude],
             8 => ['source' =>  "-43°38'19.39\"S -70°38'21.23\"W",          'latitude' =>  $latitude, 'longitude' =>  $longitude],
             9 => ['source' =>  "-43°38'19.39\"S -70°38'21.23\"W",          'latitude' =>  $latitude, 'longitude' =>  $longitude],
            10 => ['source' =>  "-43°38'19.39\"S -70°38'21.23\"W",          'latitude' =>  $latitude, 'longitude' =>  $longitude],
            11 => ['source' =>  "43°38.32316667'N 70°38.35383333'E",        'latitude' =>  $latitude, 'longitude' =>  $longitude],
            12 => ['source' =>  "-43°38.32316667'S -70°38.35383333'W",      'latitude' =>  $latitude, 'longitude' =>  $longitude],
            13 => ['source' =>  "-43°38.32316667'S  -70°38.35383333'W",     'latitude' =>  $latitude, 'longitude' =>  $longitude],
            14 => ['source' =>  "S43°38'19.39\" W70°38'21.23\"",            'latitude' => -$latitude, 'longitude' => -$longitude],
            15 => ['source' =>  "43°38'19.39\"S 70°38'21.23\"W",            'latitude' => -$latitude, 'longitude' => -$longitude],
            16 => ['source' =>  "43°38'19.39\"S;70°38'21.23\"W",            'latitude' => -$latitude, 'longitude' => -$longitude],
            17 => ['source' =>  "43°38'19,39\"S 70°38'21,23\"W",            'latitude' => -$latitude, 'longitude' => -$longitude],
            18 => ['source' =>  "N43°38’19.39“ E70 38 21.23''",             'latitude' =>  $latitude, 'longitude' =>  $longitude],
            19 => ['source' =>  "43.638719444444;70.639230555556",          'latitude' =>  $latitude, 'longitude' =>  $longitude],
            20 => ['source' =>  "43,638719444444;70,639230555556",          'latitude' =>  $latitude, 'longitude' =>  $longitude],
            21 => ['source' =>  "+43.638719444444;+70.639230555556",        'latitude' =>  $latitude, 'longitude' =>  $longitude],
            22 => ['source' =>  "-43,638719444444;+70,639230555556",        'latitude' => -$latitude, 'longitude' =>  $longitude],
            23 => ['source' =>  "+43.638719444444; -70.639230555556",       'latitude' =>  $latitude, 'longitude' => -$longitude],
            24 => ['source' =>  "+43.0;-70.639230555556",                   'latitude' =>  43,        'longitude' => -$longitude],
            25 => ['source' =>  "-43;-70.639230555556",                     'latitude' => -43,        'longitude' => -$longitude],
            26 => ['source' =>  "-43;-70.639230555556",                     'latitude' => -43,        'longitude' => -$longitude],
            27 => ['source' =>  "43.1;-70",                                 'latitude' =>  43.1,      'longitude' => -70],
            28 => ['source' =>  "-43;-70",                                  'latitude' => -43,        'longitude' => -70],
            29 => ['source' =>  "-43,-70",                                  'latitude' => -43,        'longitude' => -70],
            30 => ['source' =>  "43.638719444444,70.639230555556",          'latitude' =>  $latitude, 'longitude' =>  $longitude],
            31 => ['source' =>  "43.638719444444°,70.639230555556°",        'latitude' =>  $latitude, 'longitude' =>  $longitude],
            32 => ['source' =>  "+43.638719444444°,-70.639230555556°",      'latitude' =>  $latitude, 'longitude' => -$longitude],
            33 => ['source' =>  "-43.638719444444°,+70.639230555556°",      'latitude' => -$latitude, 'longitude' =>  $longitude],
            34 => ['source' =>  "N43.638719444444°,E70.639230555556°",      'latitude' =>  $latitude, 'longitude' =>  $longitude],
            35 => ['source' =>  "S43.638719444444°,E70.639230555556°",      'latitude' => -$latitude, 'longitude' =>  $longitude],
            36 => ['source' =>  "S-43.638719444444°,W-70.639230555556°",    'latitude' =>  $latitude, 'longitude' =>  $longitude],
            37 => ['source' =>  "N43 38 19.39  E70 38 21.23",               'latitude' =>  $latitude, 'longitude' =>  $longitude],
            38 => ['source' =>  "43 38 19.39N 70 38 21.23E",                'latitude' =>  $latitude, 'longitude' =>  $longitude],
            39 => ['source' =>  "N43°38’19.39“ E70°38’21.23“",              'latitude' =>  $latitude, 'longitude' =>  $longitude],
            40 => ['source' =>  "+43,638719444444, -70,639230555556",       'latitude' =>  $latitude, 'longitude' => -$longitude],
            41 => ['source' =>  "+43,638719444444, -70.639230555556",       'latitude' =>  $latitude, 'longitude' => -$longitude],
            50 => ['source' =>  "58C 539154 1357813",                       'latitude' =>  '-77.850005797629', 'longitude' => '166.6666604822'],
            51 => ['source' =>  "58C 539154.2419 1357813.64",               'latitude' =>  '-77.850000003622', 'longitude' => '166.66666999893'],
            52 => ['source' =>  "58C 539154m 1357813m",                     'latitude' =>  '-77.850005797629', 'longitude' => '166.6666604822'],
            53 => ['source' =>  "58C 539154.2419m 1357813.64m",             'latitude' =>  '-77.850000003622', 'longitude' => '166.66666999893'],
            54 => ['source' =>  "58C 539154,2419 1357813,64",               'latitude' =>  '-77.850000003622', 'longitude' => '166.66666999893'],
            70 => ['source' =>  "30V WH 62367 06531",                       'latitude' =>  '56.000001651313', 'longitude' => '-1.9999940273861'],
            71 => ['source' =>  "30V WH 6236706531",                        'latitude' =>  '56.000001651313', 'longitude' => '-1.9999940273861'],
            72 => ['source' =>  "30V WH6236706531",                         'latitude' =>  '56.000001651313', 'longitude' => '-1.9999940273861'],
            73 => ['source' =>  "30VWH 6236706531",                         'latitude' =>  '56.000001651313', 'longitude' => '-1.9999940273861'],
            74 => ['source' =>  "30VWH6236706531",                          'latitude' =>  '56.000001651313', 'longitude' => '-1.9999940273861'],
            75 => ['source' =>  "30V WH 6236 0653",                         'latitude' =>  '55.951201763317', 'longitude' => '-2.9001383024235'],
            76 => ['source' =>  "30V WH 62360653",                          'latitude' =>  '55.951201763317', 'longitude' => '-2.9001383024235'],
            77 => ['source' =>  "30V WH 623 065",                           'latitude' =>  '55.945958625046', 'longitude' => '-2.9900247883054'],
            78 => ['source' =>  "30V WH 623065",                            'latitude' =>  '55.945958625046', 'longitude' => '-2.9900247883054'],
            79 => ['source' =>  "30V WH 62 06",                             'latitude' =>  '55.945428908329', 'longitude' => '-2.9990072958555'],
            80 => ['source' =>  "30V WH 6206",                              'latitude' =>  '55.945428908329', 'longitude' => '-2.9990072958555'],
            81 => ['source' =>  "30V WH 6 0",                               'latitude' =>  '55.945375002142', 'longitude' => '-2.9999039319904'],
            82 => ['source' =>  "30V WH 60",                                'latitude' =>  '55.945375002142', 'longitude' => '-2.9999039319904'],
            83 => ['source' =>  "30V WH 00",                                'latitude' =>  '55.945375002179', 'longitude' => '-3.0'],
            84 => ['source' =>  "30V WH",                                   'latitude' =>  '55.945375002179', 'longitude' => '-3.0'],
            85 => ['source' =>  "42T XP 32219 33052",                       'latitude' =>  '43.638711149443', 'longitude' => '70.639223030708'],
        ];
    }

    public function testLatLonFromString()
    {
        foreach ($this->map as $index => $test) {
            $latLon = LatLng::latLonFromString($test['source']);
            self::assertEquals($test['latitude'],  $latLon->latitude,  "In Test No. $index");
            self::assertEquals($test['longitude'], $latLon->longitude, "In Test No. $index");
        }
    }

    /**
     * @group singleLatLonFromStringHelper
     */
    public function testLatLonFromStringSingle()
    {
        $index = 51;
        $test  = $this->map[$index];
        $latLon = LatLng::latLonFromString($test['source']);
        self::assertEquals($test['latitude'],  $latLon->latitude, "In Test No. $index");
        self::assertEquals($test['longitude'], $latLon->longitude);
    }

    /**
     * @expectedException \Exception
     */
    public function testLatLonFromStringWithEmptyResultAndException1()
    {
        $string = 'Bullshit';
        $latLng = LatLng::latLngCoordinatesFromUTMString($string);
        self::assertNull($latLng);

        // should throw an exception
        LatLng::latLonFromString($string);
    }

    /**
     * @expectedException \Exception
     */
    public function testLatLonFromStringWithEmptyResultAndException2()
    {
        $string = 'Bullshit';
        $latLng = LatLng::latLngCoordinatesFromMGRSString($string);
        self::assertNull($latLng);

        // should throw an exception
        LatLng::latLonFromString($string);
    }

    /**
     * @expectedException \Exception
     */
    public function testLatLonFromStringWithEmptyResultAndException3()
    {
        $string = 'Bullshit';
        $latLng = LatLng::latLngCoordinatesFromDegreesMinutesSecondsString($string);
        self::assertNull($latLng);

        // should throw an exception
        LatLng::latLonFromString($string);
    }

    /**
     * @expectedException \Exception
     */
    public function testLatLonFromStringWithEmptyResultAndException4()
    {
        $string = 'Bullshit';
        $latLng = LatLng::latLngCoordinatesFromDegreesMinutesString($string);
        self::assertNull($latLng);

        // should throw an exception
        LatLng::latLonFromString($string);
    }

    /**
     * @expectedException \Exception
     */
    public function testLatLonFromStringWithEmptyResultAndException5()
    {
        $string = 'Bullshit';
        $latLng = LatLng::latLngCoordinatesFromDegreesString($string);
        self::assertNull($latLng);

        // should throw an exception
        LatLng::latLonFromString($string);
    }

    /**
     * @expectedException \Exception
     */
    public function testLatLonFromStringWithException3()
    {
        LatLng::latLonFromString("+43;638719444444,-70.639230555556");
    }

    /**
     * @expectedException \Exception
     */
    public function testLatLonFromStringWithException4()
    {
        LatLng::latLonFromString("+43;");
    }

    /**
     * @expectedException \Exception
     */
    public function testLatLonFromStringWithException5()
    {
        LatLng::latLonFromString("30V WH 2367 06531");
    }

    /**
     * @expectedException \Exception
     */
    public function testLatLonFromStringWithException6()
    {
        LatLng::latLonFromString("+99.12°;-190.34°");
    }

    /**
     * @expectedException \Exception
     */
    public function testLatLonFromStringWithException7()
    {
        LatLng::latLonFromString("91I 12345 67890");
    }
}
