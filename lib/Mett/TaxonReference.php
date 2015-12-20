<?php

namespace Mett;

class TaxonReference
{
    public $taxonId               = null;
    public $referenceId           = null;


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
            return sprintf("'%s'", $value);
        }
    }


    /**
     * Returns a single MySQL INSERT statement for the taxon_reference instance
     *
     * @return string
     */
    public function getInsertSql()
    {
        $a = sprintf("INSERT IGNORE INTO `taxon_reference` (
    `taxonId`, `referenceId`
) VALUES (
    %s, %s
);\n", static::e($this->taxonId), static::e($this->referenceId));

        return $a;
    }


    /**
     * Return a MySQL DROP TABLE statement for `taxon_reference`
     *
     * @return string
     */
    public static function getDropSql()
    {
        return "DROP TABLE IF EXISTS `taxon_reference`;\n\n";
    }


    /**
     * Returns a MySQL CREATE TABLE statement for `taxon_reference`
     *
     * @return string
     */
    public static function getCreateSql()
    {
        return "CREATE TABLE IF NOT EXISTS `taxon_reference` (
    `taxonId` VARCHAR(48) NOT NULL,
    `referenceId` VARCHAR(48) NOT NULL,
    PRIMARY KEY (`taxonId`, `referenceId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;\n\n";
    }
}
