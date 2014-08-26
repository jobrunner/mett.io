<?php
namespace Mett;

class LatLng
{
    /**
     * Latitude in decimal degrees (-180.0 through 180.0)
     * @var float
     */
    public $latitude;

    /**
     * Longitude in decimal degrees (-90.0 through 90.0)
     * @var float
     */
    public $longitude;

    /**
     * Geodetic datum. Tbd.
     * @var object
     */
    public $datum;


    public function __construct($latitude, $longitude, $datum = null)
    {
        self::validateLatitude($latitude);
        self::validateLongitude($longitude);

        $this->latitude  = $latitude;
        $this->longitude = $longitude;
        $this->datum     = null;
    }

    public static function validateLatitude($latitude)
    {
        if (-180.0 <= (float)$latitude && (float)$latitude <= 180.0) {
            return true;
        }

        throw new \Exception('Not a valid latitude');
    }

    public static function validateLongitude($longitude)
    {
        if (-90.0 <= (float)$longitude && (float)$longitude <= 90.0) {
            return true;
        }

        throw new \Exception('Not a valid longitude');
    }

    public static function latLonFromString($latLngString)
    {
        $latLng = self::latLngCoordinatesFromDegreesMinutesSecondsString($latLngString);

        if ($latLng != null) {

            return new self($latLng->latitude, $latLng->longitude);
        }

        $latLng = self::latLngCoordinatesFromDecimalString($latLngString);

        if (null != $latLng) {

            return new self($latLng->latitude, $latLng->longitude);
        }

        throw new \Exception('No valid coordinates');
    }

    public static function latLngCoordinatesFromDegreesMinutesSecondsString($latLonString)
    {
        $regex  = '#(([NSEWO]{0,1})\s*([\-+]{0,1}[0-9]+)[^0-9]+([0-9]+)[^0-9]+([0-9]+([.,][0-9]+){0,1})[^0-9NSEWO]*([NSEWO]{0,1}))';
        $regex .=  '[\s;,]+';
        $regex .=  '(([NSEWO]{0,1})\s*([\-+]{0,1}[0-9]+)[^0-9]+([0-9]+)[^0-9]+([0-9]+([.,][0-9]+){0,1})[^0-9NSEWO]*([NSEWO]{0,1}))#u';

        $latitude  = null;
        $longitude = null;

        if (preg_match($regex, $latLonString, $matches)) {
            $degrees = $matches[3];
            $minutes = $matches[4];
            $seconds = (float)str_replace(',', '.', $matches[5]);
            $heading = empty($matches[2]) ? $matches[7] : $matches[2];
            $heading = str_replace('O', 'E', $heading);

            $firstCoord = self::decimalFromDegreesMinutesSeconds($degrees, $minutes, $seconds, $heading);

            if ($firstCoord['type'] == 'latitude') {
                $latitude  = $firstCoord['decimal'];
            } else {
                $longitude = $firstCoord['decimal'];
            }

            $degrees = $matches[10];
            $minutes = $matches[11];
            $seconds = str_replace(',', '.', $matches[12]);
            $heading = empty($matches[9]) ? $matches[14] : $matches[9];
            $heading = str_replace('O', 'E', $heading);

            $secondCoord  = self::decimalFromDegreesMinutesSeconds($degrees, $minutes, $seconds, $heading);

            if ($secondCoord['type'] == 'latitude') {
                $latitude = $secondCoord['decimal'];
            } else {
                $longitude = $secondCoord['decimal'];
            }

            return (object)['latitude' => $latitude, 'longitude' => $longitude];
        } else {
            return null;
        }
    }

    public static function latLngCoordinatesFromDecimalString($latLngString)
    {
        if (preg_match('#([+\-]){0,1}([0-9]+(([.,])[0-9]+){0,1})([^0-9.+\-]+)([+\-]){0,1}([0-9]+(([.,])[0-9]+){0,1})#u', $latLngString, $matches)) {

            $decimalPointLatitude  = empty($matches[4]) ? '' : $matches[4];
            $decimalPointLongitude = empty($matches[9]) ? '' : $matches[9];
            $separator             = $matches[5];


            // Heureka if-Orgie! :-(
            if (!empty($decimalPointLatitude)) {
                if (!empty($decimalPointLongitude)) {

                    if (false !== strstr($separator, $decimalPointLongitude)) {
                        return null;
                    }

                    if ($decimalPointLatitude != $decimalPointLongitude) {
                        return null;
                    }

                } else {

                    if (false !== strstr($separator, $decimalPointLatitude)) {
                        return null;
                    }
                }
            } else {
                if (!empty($decimalPointLongitude)) {
                    if (false !== strstr($separator, $decimalPointLongitude)) {
                        return null;
                    }
                }
            }

            $latitude = (float)str_replace(',', '.', $matches[2]);

            if ($matches[1] == '-') {
                $latitude = $latitude * (-1);
            }

            $longitude = (float)str_replace(',', '.', $matches[7]);

            if ($matches[6] == '-') {
                $longitude = $longitude * (-1);
            }

            return (object)['latitude' => $latitude, 'longitude' => $longitude];
        }

        return null;
    }

    public static function decimalFromDegreesMinutesSeconds($degrees, $minutes, $seconds, $heading)
    {
        $seconds   = ($seconds / 60);
        $minutes   = ($minutes + $seconds);
        $minutes   = ($minutes / 60);
        $decimal   = ($degrees + $minutes);

        if (('S' == $heading) || ('W' == $heading)) {
            $decimal = -1 * $decimal;
        }

        return ['decimal' => $decimal,
                'type'    => ($heading == 'N' || $heading == 'S') ? 'latitude' : 'longitude'];
      }
}