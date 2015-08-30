<?php
namespace Mett;

class Author
{
    public $familyName;
    public $givenName;
    public $fullGivenName;

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
        $familyName = null;
        $givenName  = null;

        if (preg_match('/([\w\s-]+)((?:(?:\s|-)[A-Z][a-z]{0,1}\.)*)(\s(?:de|von|du|van der)){0,1}$/u', $authorString, $matches)) {

            $familyName = trim($matches[1]);
            $givenName  = trim($matches[2]);

            if (!empty($matches[3])) {
                $familyName = $familyName . ' ' . trim($matches[3]);
            }
        }

        return (new self([
            'familyName' => $familyName,
            'givenName' => $givenName,
            'fullGivenName' => null,
        ]));
    }
}