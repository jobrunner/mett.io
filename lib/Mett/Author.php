<?php
namespace Mett;

class Author
{
    public $familyName;
    public $givenName;
    public $fullGivenName;
    public $altFamilyName;
    public $altGivenName;

    public function __construct(array $defaults = [])
    {
        foreach ($defaults as $name => $value) {
            $this->{$name} = $value;
        }
    }

    /**
     * <familyName> [<initial>.[-], ...]
     *
     * @param $authorString
     *
     * @return \Mett\Author
     */
    public static function initWithString($authorString)
    {
        $familyName    = null;
        $givenName     = null;
        $altFamilyName = null;
        $altGivenName  = null;

        $alternativeName = null;

        if (preg_match('/\[=\s+([^\]]+)\]/u', $authorString, $matches)) {
            $alternativeName = trim($matches[1]);

            // remove alternate name from authorString
            $authorString    = trim(preg_replace('/\[=\s+([^\]]+)\]/u', '', $authorString));

            // decide whether alternative is a given name. If not it will be treated as family name
            if (preg_match('/^((?:-{0,1}[A-Z][a-z]{0,1}\.)*)$/u', $alternativeName, $altMatches)) {
                $altGivenName  = $alternativeName;
            } else {
                $altFamilyName = $alternativeName;
            }
        }

        if (preg_match('/([\w\s-]+)((?:(?:\s|-)[A-Z][a-z]{0,1}\.)*)(\s(?:de|von|du|van der)){0,1}$/u', $authorString, $matches)) {
            $familyName = trim($matches[1]);
            $givenName  = trim($matches[2]);

            if (!empty($matches[3])) {
                $familyName = $familyName . ' ' . trim($matches[3]);
            }
        }

        return (new self([
            'familyName'    => $familyName,
            'givenName'     => $givenName,
            'fullGivenName' => null,
            'altFamilyName' => $altFamilyName,
            'altGivenName'  => $altGivenName,
            'alt' => $alternativeName,
        ]));
    }
}