<?php

namespace Mett;

class DistributionUnits
{
    public $distributions = [];


    /**
     * Replaces occurrences of Patria ignota at the beginning of a string with PIG
     *
     * @param $distributionString
     *
     * @return mixed
     */
    public static function filterPatriaIgnota($distributionString)
    {
        return preg_replace(
            '/^("PATRIA IGNOTA"|PATRIA IGNOTA|"DISTR. UNKNOWN"|DISTR. UNKNOWN)/i',
            'PIG',
            $distributionString
        );
    }

    public static function split($distributionString)
    {
        return preg_split(
              "/"
               # level 2 "free text". E.g. E: "Caucasus"
               # Return value WITHOUT quotes!
            . '\s*\"([^\"]+)\"\s*'
            . '|'
               # level 3 (additional free text to level 2).
               # Result value is WITH brackets to distinguish the previous case
            . '\s*(\([^?)]+\))\s*'
            . '|'
            . '\s+'
            . '/',
             $distributionString,
             0,
             PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    }

    public static function tokenize($distributionString)
    {
        $distributionString = static::filterPatriaIgnota($distributionString);
        $tokens             = static::split($distributionString);
        $tokenized          = [];

        foreach ($tokens as $token) {

            $introduced = false;
            $doubtful   = false;
            $level      = null;
            $text       = null;

            $token      = rtrim($token, ':');

            // Extract introduction "flag" i
            // e.g. E or Ei or SP or SPi or AFR AFRi
            if (preg_match('/^([A-Z]{1,3})(i){0,1}$/', $token, $matches)) {
                $token      = $matches[1];
                $introduced = !empty($matches[2]);
            }

            // Extract doubtful distribution
            // e.g. E or E(?) or SP or SP(?) or AFR AFR(?)
            if (preg_match('/^([A-Z]{1,3})(\(\?\))$/', $token, $matches)) {
                $token    = $matches[1];
                $doubtful = !empty($matches[2]);
            }

            // Parse a level 0 distribution indication
            if (in_array($token, ['PAL','PIG','AFR','AUR','NAR','NTR','ORR','COS'])) {
                $level = 0;
            }

            // Parse a level 1 distribution indication
            if (in_array($token, ['E', 'N', 'A'])) {
                $level = 1;

                // Insert PAL before level 1
                $tokenized[] = [
                    'token'      => 'PAL',
                    'text'       => null,
                    'level'      => 0,
                    'introduced' => false,
                    'doubtful'   => false
                ];
            }

            // Parse level 2 distribution indication
            $divisionE = ['AB','AL','AN','AR','AU','AZ','BE','BH','BU','BY','CR','CT','CZ','DE','EN','FA','FI','FR','GB','GE','GG','GR','HU','IC','IR','IT','KZ','LA','LS','LT','LU','MA','MC','MD','ME','NL','NR','NT','PL','PT','RO','RU','SB','SK','SL','SP','SR','ST','SV','SZ','TR','UK','YU'];
            $divisionN = ['AG','CI','EG','LB','MO','MR','TU'];
            $divisionA = ['AE','AF','AP','BA','BT','CE','CH','CY','ES','FE','HP','IN','IQ','IS','JA','JO','KA','KI','KU','KZ','LE','MG','NE','NC','NO','NP','NW','OM','PA','QA','RU','SA','SC','SD','SE','SI','SW','SY','TD','TM','TR','UP','UZ','WP','WS','YE','AHN','BEI','CHQ','FUJ','GAN','GUA','GUI','GUX','HAI','HEB','HEI','HEN','HKG','HUB','HUN','JIA','JIL','JIX','LIA','MAC','NIN','NMO','QIN','SCH','SHA','SHG','SHN','SHX','TAI','TIA','XIN','XIZ','YUN','ZHE'];

            $countries = array_merge($divisionA, $divisionE, $divisionN);

            if (in_array($token, $countries)) {
                $level = 2;
            }

            // Parse free text distribution
            if ($level === null) {

                // Parse free text level 3 distribution
                // (Gomera, La Palma) or (Kreti) etc. -> level3 free text
                if (preg_match('/^\(([^)]+)\)$/', $token, $matches)) {
                    $level = 3;
                    $token = null;
                    $names = preg_split('/\s*,/', $matches[1]);
                    $text  = trim($names[count($names) - 1]);

                    // insert each free text level 3 distributions without the last
                    for ($i = 0; $i < count($names) - 1; $i++) {
                        $tokenized[] = [
                            'token'      => $token,
                            'text'       => trim($names[$i]),
                            'level'      => $level,
                            'introduced' => $introduced,
                            'doubtful'   => $doubtful
                        ];
                    }

                // Parse free text level 2 distribution that can be determined
                // E.g. 'Caucasus' or 'Caucasus (?)'
                } elseif (preg_match('/^([^(]+)(\(\?\)){0,1}$/', $token, $matches)) {
                    $level    = 2;
                    $token    = null;
                    $text     = trim($matches[1]);
                    $doubtful = !empty($matches[2]);
                }
            }

            $tokenized[] = [
                'token'      => $token,
                'text'       => $text,
                'level'      => $level,
                'introduced' => $introduced,
                'doubtful'   => $doubtful
            ];
        }

        return $tokenized;
    }


    public function __construct(array $reference, $distributionString)
    {
        $units  = [];
        $tokens = static::tokenize($distributionString);
        $unit   = new DistributionUnit($reference);
        $token  = array_shift($tokens);

        while (!empty($token)) {
            $unit->{"level" . $token['level']}                = $token['token'];
            $unit->{"level" . $token['level'] . "text"}       = $token['text'];
            $unit->{"level" . $token['level'] . "introduced"} = $token['introduced'];
            $unit->{"level" . $token['level'] . "doubtful"}   = $token['doubtful'];

            $lastLevel = $token['level'];
            $token     = array_shift($tokens);

            if ($lastLevel == $token['level']) {
                $units[] = $unit;
                $unit    = clone($unit);
            }

            if ($lastLevel > $token['level']) {
                $units[] = $unit;

                if ($token['level'] == 2) {
                    $reference['level0'] = $unit->level0;
                    $reference['level1'] = $unit->level1;
                }

                $unit    = new DistributionUnit($reference);
            }
        }

        $this->distributions = $units;
    }
}
