<?php
namespace Mett\Citation;

use Mett\Citation\Formatter\FormatterInterface;

class Author
{
    public $familyName;
    public $givenName;
    public $fullGivenName;
    public $altFamilyName;
    public $altGivenName;

    protected $_formatter = null;


    /**
     * Constructor
     *
     * @param array                   $defaults. Keys of the array should be public properties of the class Author
     * @param FormatterInterface|null $formatter
     */
    public function __construct(array $defaults = [], FormatterInterface $formatter = null)
    {
        foreach ($defaults as $name => $value) {
            $this->{$name} = $value;
        }

        $this->_formatter = $formatter;
    }


    /**
     * Init an Author instance with standard author format:
     * <family name>, <given name as initials or mixed with full given name and/or initials> or
     * <familyName> [<initial>.[-], ...]
     * Either <family name> or <given name> may have an alternative spelling in the
     * form [= <alternative name or initials>]
     *
     * @param $authorString
     *
     * @return Author
     */
    public static function initWithString($authorString)
    {
        $familyName      = null;
        $givenName       = null;
        $altFamilyName   = null;
        $altGivenName    = null;
        $alternativeName = null;

        // First of all, make an assumption that the author is given as <family name>, <given name>
        if (preg_match('/^([^,]+),(.*)$/u', $authorString, $matches)) {
            $familyName = trim($matches[1]);

            if (preg_match('/\[=\s+([^\]]+)\]/u', $familyName, $altFamilyNameMatches)) {
                $altFamilyName = trim($altFamilyNameMatches[1]);
                $familyName    = trim(preg_replace('/\[=\s+([^\]]+)\]/u', '', $familyName));
            }

            $givenName  = trim($matches[2]);

            if (preg_match('/\[=\s+([^\]]+)\]/u', $givenName, $altGivenNameMatches)) {
                $altGivenName = trim($altGivenNameMatches[1]);
                $givenName    = trim(preg_replace('/\[=\s+([^\]]+)\]/u', '', $givenName));
            }

            return (new self([
                'familyName'    => $familyName,
                'givenName'     => $givenName,
                'altFamilyName' => $altFamilyName,
                'altGivenName'  => $altGivenName,
            ]));
        }


        // If an alternative spelling is given it will be removed from family and given names
        // and separately stored into alternative family and given names.
        if (preg_match('/\[=\s+([^\]]+)\]/u', $authorString, $matches)) {
            $alternativeName = trim($matches[1]);

            // remove alternative name from authorString
            $authorString    = trim(preg_replace('/\[=\s+([^\]]+)\]/u', '', $authorString));

            // decide whether alternative name is an alternative given name.
            // If it is not, it will be treated as alternative family name.
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
        ]));
    }
}