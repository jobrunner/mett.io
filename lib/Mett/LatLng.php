<?php
namespace Mett;

// for lean experiments: input common geographic position as a single string.
// Assumes all reference ellipsoids are in WGS 84
class LatLng
{
    /**
     * Latitude in decimal degrees (-90.0 through 90.0)
     * @var float
     */
    public $latitude;

    /**
     * Longitude in decimal degrees (-180.0 through 180.0)
     * @var float
     */
    public $longitude;

    /**
     * Geodetic datum. Tbd.
     * @var object
     */
    public $datum;


    /**
     * Constructs a LatLng object.
     *
     * @param        $latitude
     * @param        $longitude
     * @param string $datum
     */
    public function __construct($latitude, $longitude, $datum = 'WGS84')
    {
        self::validateLatitude($latitude);
        self::validateLongitude($longitude);

        $this->latitude  = $latitude;
        $this->longitude = $longitude;
        $this->datum     = $datum;
    }

    /**
     * Very basic latitude validation.
     *
     * @param $latitude
     *
     * @return bool
     * @throws \Exception
     */
    public static function validateLatitude($latitude)
    {
        if (-90.0 <= (float)$latitude && (float)$latitude <= 90.0) {
            return true;
        }

        throw new \Exception("Not a valid latitude $latitude");
    }

    /**
     * Very basic longitude validation.
     *
     * @param $longitude
     *
     * @return bool
     * @throws \Exception
     */
    public static function validateLongitude($longitude)
    {
        if (-180.0 <= (float)$longitude && (float)$longitude <= 180.0) {
            return true;
        }

        throw new \Exception("Not a valid longitude $longitude");
    }


    /**
     * Takes an string with a geographic position and tries to calculate a
     * geodetic coordinate (latitude and longitude).
     * This is the main method of the class.
     *
     * @param        $latLngString
     * @param string $date
     *
     * @return LatLng|null
     * @throws \Exception
     */
    public static function latLonFromString($latLngString, $date = 'WGS84')
    {
        if (null != ($latLng = self::latLngCoordinatesFromDegreesMinutesSecondsString($latLngString, $date))) {
            return $latLng;
        }

        if (null != ($latLng = self::latLngCoordinatesFromDegreesMinutesString($latLngString, $date))) {
            return $latLng;
        }

        if (null != ($latLng = self::latLngCoordinatesFromDegreesString($latLngString, $date))) {
            return $latLng;
        }

        if (null != ($latLng = self::latLngCoordinatesFromUTMString($latLngString))) {
            return $latLng;
        }

        if (null != ($latLng = self::latLngCoordinatesFromMGRSString($latLngString))) {
            return $latLng;
        }

        throw new \Exception('No valid coordinates detected');
    }


    /**
     * Tries to convert a coordinate in degrees/minutes/seconds format into a LatLng instance.
     *
     * @param        $latLonString
     * @param string $date
     *
     * @return LatLng|null
     */
    public static function latLngCoordinatesFromDegreesMinutesSecondsString($latLonString, $date = 'WGS84')
    {
        $regex  = '#^(([NSEWO]{0,1})\s*([\-+]{0,1}\d{1,3})[^0-9.,;]+(\d{1,2})[^0-9.,]+(\d+([.,]\d+){0,1})[^0-9NSEWO]*([NSEWO]{0,1}))';
        $regex .=   '[\s;,]+';
        $regex .=   '(([NSEWO]{0,1})\s*([\-+]{0,1}\d{1,3})[^0-9.,;]+(\d{1,2})[^0-9.,]+(\d+([.,]\d+){0,1})[^0-9NSEWO]*([NSEWO]{0,1}))$#u';

        if (false == preg_match($regex, $latLonString, $matches)) {

            return null;
        }

        $latitude   = null;
        $longitude  = null;
        $degrees    = $matches[3];
        $minutes    = $matches[4];
        $seconds    = (float)str_replace(',', '.', $matches[5]);
        $heading    = empty($matches[2]) ? $matches[7] : $matches[2];
        $heading    = str_replace('O', 'E', $heading);
        $coord      = self::_decimalFromDegreesMinutesSeconds($degrees, $minutes, $seconds, $heading);

        if ('latitude' == $coord['type']) {
            $latitude  = $coord['decimal'];
        } else {
            $longitude = $coord['decimal'];
        }

        $degrees    = $matches[10];
        $minutes    = $matches[11];
        $seconds    = str_replace(',', '.', $matches[12]);
        $heading    = empty($matches[9]) ? (empty($matches[14]) ? $matches[7] : $matches[14]) : $matches[9];
        $heading    = str_replace('O', 'E', $heading);
        $coord      = self::_decimalFromDegreesMinutesSeconds($degrees, $minutes, $seconds, $heading);

        if ('latitude' == $coord['type']) {
            $latitude = $coord['decimal'];
        } else {
            $longitude = $coord['decimal'];
        }

        return new self($latitude, $longitude, $date);
    }


    /**
     * Tries to convert a coordinate in degrees/minutes format into a LatLng instance.
     *
     * @param        $latLonString
     * @param string $date
     *
     * @return LatLng|null
     */
    public static function latLngCoordinatesFromDegreesMinutesString($latLonString, $date = 'WGS84')
    {
        $regex  = '#^(([NSEWO]{0,1})\s*([\-+]{0,1}\d+)\x{00B0}{1}\s*(\d+([.,]\d+){0,1})[^0-9NSEWO]*([NSEWO]{0,1}))';
        $regex .=    '[\s;,]+';
        $regex .=   '(([NSEWO]{0,1})\s*([\-+]{0,1}\d+)\x{00B0}{1}\s*(\d+([.,]\d+){0,1})[^0-9NSEWO]*([NSEWO]{0,1}))$#ui';

        if (false == preg_match($regex, $latLonString, $matches)) {

            return null;
        }

        $latitude   = null;
        $longitude  = null;
        $degrees    = $matches[3];
        $minutes    = (float)str_replace(',', '.', $matches[4]);
        $heading    = empty($matches[2]) ? $matches[6] : $matches[2];
        $heading    = str_replace('O', 'E', $heading);
        $coord      = self::_decimalFromDegreesMinutesSeconds($degrees, $minutes, 0, $heading);

        if ('latitude' == $coord['type']) {
            $latitude  = $coord['decimal'];
        } else {
            $longitude = $coord['decimal'];
        }

        $degrees    = $matches[9];
        $minutes    = str_replace(',', '.', $matches[10]);
        $heading    = empty($matches[8]) ?  (empty($matches[12]) ? $matches[6] : $matches[12]) : $matches[8];
        $heading    = str_replace('O', 'E', $heading);
        $coord      = self::_decimalFromDegreesMinutesSeconds($degrees, $minutes, 0, $heading);

        if ('latitude' == $coord['type']) {
            $latitude = $coord['decimal'];
        } else {
            $longitude = $coord['decimal'];
        }

        return new self($latitude, $longitude, $date);
    }


    /**
     * Tries to convert a coordinate in degrees format into a LatLng instance.
     *
     * @param        $latLonString
     * @param string $date
     *
     * @return LatLng|null
     */
    public static function latLngCoordinatesFromDegreesString($latLonString, $date = 'WGS84')
    {
        $regex  = '#^((([NSEWO]{0,1})\s*([-|\+]{0,1}\d{1,3}([.,]\d+)*)[^0-9.,NSEWO]*([NSEWO]{0,1}))';
        $regex .=   '[\s;,]+';
        $regex .=   '(([NSEWO]{0,1})\s*([-|\+]{0,1}\d{1,3}([.,]\d+)*)[^0-9.,NSEWO]*([NSEWO]{0,1})))$#u';

        if (false == preg_match($regex, $latLonString, $matches)) {

            return null;
        }

        $latitude   = null;
        $longitude  = null;
        $degrees    = (float)str_replace(',', '.', $matches[4]);
        $heading    = empty($matches[3]) ? $matches[6] : $matches[3];
        $heading    = str_replace('O', 'E', $heading);
        $coord      = self::_decimalFromDegreesMinutesSeconds($degrees, 0, 0, $heading);

        if (null == $coord['type'] || 'latitude' == $coord['type']) {
            $latitude  = $coord['decimal'];
        } else {
            $longitude = $coord['decimal'];
        }

        $degrees    = str_replace(',', '.', $matches[9]);
        $heading    = empty($matches[8]) ?  (empty($matches[11]) ? $matches[6] : $matches[11]) : $matches[8];
        $heading    = str_replace('O', 'E', $heading);
        $coord      = self::_decimalFromDegreesMinutesSeconds($degrees, 0, 0, $heading);

        if ('latitude' == $coord['type']) {
            $latitude = $coord['decimal'];
        } else {
            $longitude = $coord['decimal'];
        }

        return new self($latitude, $longitude, $date);
    }


    /**
     * Tries to convert a coordinate in UTM format into a LatLng instance.
     *
     * @param $utmString
     *
     * @return LatLng|null
     */
    public static function latLngCoordinatesFromUTMString($utmString)
    {
        $regex = '#^(\d{1,2})([A-HJ-NP-Z])\s+(\d+(?:[.,]\d*){0,1})(?:mE|m|)\s*(?:;|)\s*(\d+(?:(?:\.|,)\d+){0,1})(?:mN|m||)$#ui';

        if (false == preg_match($regex, $utmString, $matches)) {

            return null;
        }
        $zoneNumber  = (int) $matches[1];
        $zoneLetter  = strtoupper($matches[2]);
        $easting     = (float)str_replace(',', '.', $matches[3]);
        $northing    = (float)str_replace(',', '.', $matches[4]);
        $latLng      = self::_utmToLatLng($easting, $northing, $zoneLetter, $zoneNumber);

        return new self($latLng->latitude, $latLng->longitude, 'WGS84');
    }


    /**
     * Tries to convert a coordinate in MGRS format into a LatLng instance.
     *
     * @param $mgrsString
     *
     * @return LatLng|null
     */
    public static function latLngCoordinatesFromMGRSString($mgrsString)
    {
        // 30V WH 62367 06531
        if (false == preg_match('#^(\d+)([A-Z])\s*([A-Z])([A-Z])([\d ]*)$#i', $mgrsString, $matches)) {

            return null;
        }

        $eastingNorthing = str_replace(' ', '', $matches[5]);

        if (strlen($eastingNorthing) % 2 != 0) {

            return null;
        }

        $zoneNumber     = $matches[1];
        $zoneLetter     = $matches[2];
        $eastingLetter  = $matches[3];
        $northingLetter = $matches[4];
        $easting        = substr($eastingNorthing, 0, strlen($eastingNorthing) / 2);
        $northing       = substr($eastingNorthing, strlen($eastingNorthing) / 2, strlen($eastingNorthing) / 2);
        $latLng         = self::_mgrsToLatLng($zoneNumber, $zoneLetter, $eastingLetter, $northingLetter, $easting, $northing);

        return new self($latLng->latitude, $latLng->longitude, 'WGS84');
    }


    /**
     * Helper method that calculates the decimal representation and the
     * possible type of coordinate component (latitude or longitude) from
     * degrees, minutes, seconds and heading.
     *
     * @param $degrees  float
     * @param $minutes  float
     * @param $seconds  float
     * @param $heading  string N: heading north, S: south, E\O: east, W: west
     *
     * @return array
     */
    protected static function _decimalFromDegreesMinutesSeconds($degrees, $minutes, $seconds, $heading)
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

    /**
     * Temporary implementation of a MGRS to Latitude/Longitude coordinate transformation.
     *
     * @param $zoneNumber
     * @param $zoneLetter
     * @param $eastingLetter
     * @param $northingLetter
     * @param $easting
     * @param $northing
     *
     * @return Object
     */
    protected static function _mgrsToLatLng($zoneNumber, $zoneLetter, $eastingLetter, $northingLetter, $easting, $northing)
    {
        $northingLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V'];

        $set  = (($zoneNumber - 1) % 6) + 1;
        $eID  = ord($eastingLetter) - 64;

        if ($eID > 8) {
            $eID--; // Offset for no I character
        }

        if ($eID > 14) {
            $eID--; // Offset for no O character
        }

        $e   = ($eID - (8 * (($set - 1) % 3)) ) * 100000.0;
        $nID = array_search($northingLetter, $northingLetters);

        // Northing ID offset for sets 2, 4 and 6
        if ($set % 2 == 0) {
            $nID -= 5;
        }

        if ($nID < 0) {
            $nID += 20;
        }

        $n   = (($nID * 100000.0) % 1000000) * 1.0;
        $arr = ['C' => 1100000.0,
                'D' => 2000000.0,
                'E' => 2800000.0,
                'F' => 3700000.0,
                'G' => 4600000.0,
                'H' => 5500000.0,
                'J' => 6400000.0,
                'K' => 7300000.0,
                'L' => 8200000.0,
                'M' => 9100000.0,
                'N' => 0.0,
                'P' => 800000.0,
                'Q' => 1700000.0,
                'R' => 2600000.0,
                'S' => 3500000.0,
                'T' => 4400000.0,
                'U' => 5300000.0,
                'V' => 6200000.0,
                'W' => 7000000.0,
                'X' => 7900000.0];
        $min = $arr[$zoneLetter];

        while ($n < $min) {
            $n += 1000000;
        }

        return self::_utmToLatLng($e + $easting, $n + $northing, $zoneLetter, $zoneNumber);
    }

    /**
     * Temporary implementation of a UTM to Latitude/Longitude coordinate transformation.
     *
     * @param $easting
     * @param $northing
     * @param $zoneLetter
     * @param $zoneNumber
     *
     * @return Object
     */
    protected static function _utmToLatLng($easting, $northing, $zoneLetter, $zoneNumber)
    {
        // {{{ WGS 84 Ellipsoid
        // Equatorial radius a in m
        $a = 6378137.0;

        // Polar Radius b in m
        $b = 6356752.314; // minor axis
        // }}}

        $eSquared        = (($a * $a) - ($b * $b)) / ($a * $a);
        $centralScale    = 0.9996;

        $ePrimeSquared   = $eSquared / (1.0 - $eSquared);
        $e1              = (1 - sqrt(1 - $eSquared)) / (1 + sqrt(1 - $eSquared));

        $x               = $easting - 500000.0;;
        $y               = $northing;

        $longitudeOrigin = ($zoneNumber - 1.0) * 6.0 - 180.0 + 3.0;

        // Correct y for southern hemisphere
        if ((ord($zoneLetter) - ord("N")) < 0) {
            $y -= 10000000.0;
        }

        $m = $y / $centralScale;

        $mu = $m / ($a * (1.0 - $eSquared / 4.0
                        - 3.0 * $eSquared * $eSquared / 64.0
                        - 5.0 * pow($eSquared, 3.0) / 256.0
                         )
                   );

        $phi1Rad = $mu + (3.0 * $e1 / 2.0 - 27.0 * pow($e1, 3.0) / 32.0) * sin(2.0 * $mu)
                       + (21.0 * $e1 * $e1 / 16.0 - 55.0 * pow($e1, 4.0) / 32.0) * sin(4.0 * $mu)
                       + (151.0 * pow($e1, 3.0) / 96.0) * sin(6.0 * $mu);

        $n = $a / sqrt(1.0 - $eSquared * sin($phi1Rad) * sin($phi1Rad));

        $t = tan($phi1Rad) * tan($phi1Rad);

        $c = $ePrimeSquared * cos($phi1Rad) * cos($phi1Rad);

        $r = $a * (1.0 - $eSquared) / pow(1.0 - $eSquared * sin($phi1Rad) * sin($phi1Rad), 1.5);

        $d = $x / ($n * $centralScale);

        $latitude   = ($phi1Rad
                    - ($n * tan($phi1Rad) / $r)
                    * ($d * $d / 2.0
                        - (
                            5.0 + (3.0 * $t) + (10.0 * $c)
                            - (4.0 * $c * $c)
                            - (9.0 * $ePrimeSquared)
                        ) * pow($d, 4.0) / 24.0
                        + (61.0
                            + (90.0 * $t)
                            + (298.0 * $c)
                            + (45.0 * $t * $t)
                            - (252.0 * $ePrimeSquared)
                            - (3.0 * $c * $c)
                        )
                        * pow($d, 6.0)
                    / 720.0))
                    * (180.0 / pi() );

      $longitude    = $longitudeOrigin
                    + (
                        ($d - (1.0 + 2.0 * $t + $c) * pow($d, 3.0) / 6.0
                            + (5.0
                                - (2.0 * $c)
                                + (28.0 * $t)
                                - (3.0 * $c * $c)
                                + (8.0 * $ePrimeSquared)
                                + (24.0 * $t * $t)
                            )
                            * pow($d, 5.0)
                            / 120.0)
                        / cos($phi1Rad)
                    )
                    * (180.0 / pi());

        return (object)['latitude'  => $latitude,
                        'longitude' => $longitude];
    }
}