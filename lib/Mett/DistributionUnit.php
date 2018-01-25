<?php

namespace Mett;

class DistributionUnit
{
    public static $tableDistributionUnit = 'distribution_unit';

    public $id               = null;
    public $taxonId          = null;
    public $recordSource     = null;
    public $created          = null;
    public $createdByUserId  = null;

    public $level0introduced = false;
    public $level0           = null;
    public $level0doubtful   = false;

    public $level1introduced = false;
    public $level1           = null;
    public $level1doubtful   = false;

    public $level2introduced = false;
    public $level2           = null;
    public $level2text       = null;
    public $level2doubtful   = false;

    public $level3introduced = false;
    public $level3           = null;
    public $level3text       = null;
    public $level3doubtful   = false;


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


    public function getInsertSql()
    {
        $a = sprintf("INSERT IGNORE INTO `%s` (
id,
taxonId, recordSource, created, createdByUserId,
level0, level0introduced, level0doubtful,
level1, level1introduced, level1doubtful,
level2, level2introduced, level2doubtful, level2text,
level3, level3introduced, level3doubtful, level3text) VALUES (
null,
%s, %s, %s, %u,
%s, %u, %u,
%s, %u, %u,
%s, %u, %u, %s,
%s, %u, %u, %s);\n",
        static::$tableDistributionUnit,
        static::e($this->taxonId), static::e($this->recordSource), static::e($this->created), $this->createdByUserId,
        static::e($this->level0), $this->level0introduced, $this->level0doubtful,
        static::e($this->level1), $this->level1introduced, $this->level1doubtful,
        static::e($this->level2), $this->level2introduced, $this->level2doubtful, static::e($this->level2text),
        static::e($this->level3), $this->level3introduced, $this->level3doubtful, static::e($this->level3text));

        return $a;
    }


    public static function getDropSql()
    {
        return sprintf("DROP TABLE IF EXISTS `%s`;\n\n", static::$tableDistributionUnit);
    }


    public static function getCreateSql()
    {
        return sprintf("CREATE TABLE `%s` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`taxonId` varchar(48) NOT NULL,
`recordSource` varchar(20) NOT NULL,
`created` DATETIME,
`createdByUserId` INT(11) NOT NULL DEFAULT '0',
`level0` varchar(3) NOT NULL DEFAULT 'PAL',
`level0introduced` tinyint(1) NOT NULL DEFAULT '0',
`level0doubtful` tinyint(1) NOT NULL DEFAULT '0',
`level1` varchar(1) DEFAULT NULL,
`level1introduced` tinyint(1) NOT NULL DEFAULT '0',
`level1doubtful` tinyint(1) NOT NULL DEFAULT '0',
`level2` varchar(3) DEFAULT NULL,
`level2introduced` tinyint(1) NOT NULL DEFAULT '0',
`level2text` varchar(50) DEFAULT NULL,
`level2doubtful` tinyint(1) NOT NULL DEFAULT '0',
`level3` varchar(3) DEFAULT NULL,
`level3introduced` tinyint(1) NOT NULL DEFAULT '0',
`level3text` varchar(50) DEFAULT NULL,
`level3doubtful` tinyint(1) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`),
KEY `level0` (`level0`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;\n\n", static::$tableDistributionUnit);
    }
}
