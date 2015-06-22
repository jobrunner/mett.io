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
            40 => ['source' =>  "+43,638719444444, -70,639230555556",       'latitude' =>  $latitude, 'longitude' => -$longitude],
            41 => ['source' =>  "+43,638719444444, -70.639230555556",       'latitude' =>  $latitude, 'longitude' => -$longitude],
        ];
    }

    public function testLatLonFromString()
    {
        foreach ($this->map as $index => $test) {
            $latLon = LatLng::latLonFromString($test['source']);
            self::assertEquals($test['latitude'],  $latLon->latitude, "In Test No. $index");
            self::assertEquals($test['longitude'], $latLon->longitude);
        }
    }

    /**
     * @group singleLatLonFromString
     */
    public function testLatLonFromStringSingle()
    {
        $index = 41;
        $test  = $this->map[$index];
        $latLon = LatLng::latLonFromString($test['source']);
        self::assertEquals($test['latitude'],  $latLon->latitude, "In Test No. $index");
        self::assertEquals($test['longitude'], $latLon->longitude);
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
}