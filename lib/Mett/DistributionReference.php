<?php

namespace Mett;

class DistributionReference
{
    public $distributionId        = null;
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
     * Returns a single MySQL INSERT statement for the distribution_reference instance
     *
     * @return string
     */
    public function getInsertSql()
    {
        $a = sprintf("INSERT IGNORE INTO `distribution_reference` (
    `distributionId`, `referenceId`
) VALUES (
    %s, %s
);\n", static::e($this->distributionId), static::e($this->referenceId));

        return $a;
    }


    /**
     * Return a MySQL DROP TABLE statement for `distribution_reference`
     *
     * @return string
     */
    public static function getDropSql()
    {
        return "DROP TABLE IF EXISTS `distribution_reference`;\n\n";
    }


    /**
     * Returns a MySQL CREATE TABLE statement for `distribution_reference`
     *
     * @return string
     */
    public static function getCreateSql()
    {
        return "CREATE TABLE IF NOT EXISTS `distribution_reference` (
    `distributionId` VARCHAR(48) NOT NULL,
    `referenceId` VARCHAR(48) NOT NULL,
    PRIMARY KEY (`distributionId`, `referenceId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;\n\n";
    }
}
