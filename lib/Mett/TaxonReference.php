<?php

namespace Mett;

class TaxonReference
{
    public static $tableTaxonReference = 'taxon_reference_e1';

    public $taxonId               = null;
    public $referenceId           = null;
    public $referenceType         = null;


    /**
     * Constructor
     *
     * @param array $defaults Optional
     */
    public function __construct(array $defaults = [])
    {
        foreach ($defaults as $key => $value) {
            $this->{$key} = $value;
        }
    }


    /**
     * Helper function for data export
     *
     * @param $value
     *
     * @return string
     */
    public static function e($value)
    {
        if (empty($value)) {
            return "NULL";
        } else {
            return sprintf("'%s'", addslashes($value));
        }
    }


    /**
     * Returns a single MySQL INSERT statement for the taxon_reference instance
     *
     * @return string
     */
    public function getInsertSql()
    {
        $a = sprintf("INSERT IGNORE INTO `%s` (
    `taxonId`, `referenceId`, `referenceType`
) VALUES (
    %s, %s, %d
);\n", static::$tableTaxonReference,
       static::e($this->taxonId),
       static::e($this->referenceId),
       $this->referenceType);

        return $a;
    }


    /**
     * Return a MySQL DROP TABLE statement for `taxon_reference`
     *
     * @return string
     */
    public static function getDropSql()
    {
        return sprintf("DROP TABLE IF EXISTS `%s`;\n\n", static::$tableTaxonReference);
    }


    /**
     * Returns a MySQL CREATE TABLE statement for `taxon_reference`
     *
     * @return string
     */
    public static function getCreateSql()
    {
        return sprintf("CREATE TABLE IF NOT EXISTS `%s` (
    `taxonId` VARCHAR(48) NOT NULL,
    `referenceId` VARCHAR(48) NOT NULL,
    `referenceType` TINYINT(4) NOT NULL,
    PRIMARY KEY (`taxonId`, `referenceId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;\n\n", static::$tableTaxonReference);
    }
}
