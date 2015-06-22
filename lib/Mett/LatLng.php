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

        $latLng = self::latLngCoordinatesFromDegreesMinutesString($latLngString);

        if ($latLng != null) {

            return new self($latLng->latitude, $latLng->longitude);
        }

        $latLng = self::latLngCoordinatesFromDegreesString($latLngString);

        if (null != $latLng) {

            return new self($latLng->latitude, $latLng->longitude);
        }

        throw new \Exception('No valid coordinates');
    }

    public static function latLngCoordinatesFromDegreesMinutesSecondsString($latLonString)
    {
        $regex  = '#(([NSEWO]{0,1})\s*([\-+]{0,1}[0-9]{1,3})[^0-9.,]+([0-9]{1,2})[^0-9.,]+([0-9]+([.,][0-9]+){0,1})[^0-9NSEWO]*([NSEWO]{0,1}))';
        $regex .=  '[\s;,]+';
        $regex .=  '(([NSEWO]{0,1})\s*([\-+]{0,1}[0-9]{1,3})[^0-9.,]+([0-9]{1,2})[^0-9.,]+([0-9]+([.,][0-9]+){0,1})[^0-9NSEWO]*([NSEWO]{0,1}))#u';

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
            $heading = empty($matches[9]) ? (empty($matches[14]) ? $matches[7] : $matches[14]) : $matches[9];
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

    public static function latLngCoordinatesFromDegreesMinutesString($latLonString)
    {
        $regex  = '#^(([NSEWO]{0,1})\s*([\-+]{0,1}[0-9]+)[^0-9.,]+(\d+([.,]\d+){0,1})[^0-9NSEWO]*([NSEWO]{0,1}))';
        $regex .=  '[\s;,]+';
        $regex .=  '(([NSEWO]{0,1})\s*([\-+]{0,1}[0-9]+)[^0-9.,]+(\d+([.,]\d+){0,1})[^0-9NSEWO]*([NSEWO]{0,1}))$#u';

        $latitude  = null;
        $longitude = null;

        if (preg_match($regex, $latLonString, $matches)) {

            $degrees = $matches[3];
            $minutes = (float)str_replace(',', '.', $matches[4]);

            $heading = empty($matches[2]) ? $matches[6] : $matches[2];
            $heading = str_replace('O', 'E', $heading);

            $firstCoord = self::decimalFromDegreesMinutesSeconds($degrees, $minutes, 0, $heading);

            if ($firstCoord['type'] == 'latitude') {
                $latitude  = $firstCoord['decimal'];
            } else {
                $longitude = $firstCoord['decimal'];
            }

            $degrees = $matches[9];
            $minutes = str_replace(',', '.', $matches[10]);
            $heading = empty($matches[8]) ?  (empty($matches[12]) ? $matches[6] : $matches[12]) : $matches[8];
            $heading = str_replace('O', 'E', $heading);

            $secondCoord  = self::decimalFromDegreesMinutesSeconds($degrees, $minutes, 0, $heading);

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

    public static function latLngCoordinatesFromDegreesString($latLonString)
    {
        $regex  = '#^((([NSEWO]{0,1})\s*([-|\+]{0,1}\d{1,3}([.,]\d+)*)[^0-9.,NSEWO]*([NSEWO]{0,1}))';
        $regex .=   '[\s;,]+';
        $regex .=   '(([NSEWO]{0,1})\s*([-|\+]{0,1}\d{1,3}([.,]\d+)*)[^0-9.,NSEWO]*([NSEWO]{0,1})))#u';

        $latitude  = null;
        $longitude = null;

        if (preg_match($regex, $latLonString, $matches)) {
            $degrees = (float)str_replace(',', '.', $matches[4]);
            $heading = empty($matches[3]) ? $matches[6] : $matches[3];
            $heading = str_replace('O', 'E', $heading);

            $firstCoord = self::decimalFromDegreesMinutesSeconds($degrees, 0, 0, $heading);

            if ($firstCoord['type'] == null || $firstCoord['type'] == 'latitude') {
                $latitude  = $firstCoord['decimal'];
            } else {
                $longitude = $firstCoord['decimal'];
            }

            $degrees = str_replace(',', '.', $matches[9]);
            $heading = empty($matches[8]) ?  (empty($matches[11]) ? $matches[6] : $matches[11]) : $matches[8];
            $heading = str_replace('O', 'E', $heading);

            $secondCoord  = self::decimalFromDegreesMinutesSeconds($degrees, 0, 0, $heading);

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

    public static function decimalFromDegreesMinutesSeconds($degrees, $minutes, $seconds, $heading)
    {
        $sign      = ($degrees < 0) ? -1 : 1;
        $degrees   = abs($degrees);
        $seconds   = ($seconds / 60);
        $minutes   = ($minutes + $seconds);
        $minutes   = ($minutes / 60);
        $decimal   = ($degrees + $minutes);

        if (('S' == $heading)) {
            $decimal = -1 * $decimal;
            $heading = 'N';
        }

        if (('W' == $heading)) {
            $decimal = -1 * $decimal;
            $heading = 'E';
        }

        $decimal = $sign * $decimal;

        if ('N' == $heading) {
            $type = 'latitude';
        } elseif ('E' == $heading) {
            $type = 'longitude';
        } else {
            // unknown
            $type = null;
        }

        return ['decimal' => $decimal,
                'type'    => $type];
      }
}