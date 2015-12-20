<?php

namespace Mett;

class Reference
{
    public $id               = null;
    public $referenceType    = 0;
    public $authors          = null;
    public $year             = null;
    public $title            = null;
    public $secondaryTitle   = null;
    public $secondaryAuthors = null;
    public $tertiaryTitle    = null;
    public $tertiaryAuthors  = null;
    public $volume           = null;
    public $number           = null;
    public $pages            = null;
    public $section          = null;
    public $edition          = null;
    public $place            = null;
    public $publisher        = null;
    public $isbn             = null;


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
     * Returns a single MySQL INSERT statement for the reference instance
     *
     * @return string
     */
    public function getInsertSql()
    {
        $a = sprintf("INSERT IGNORE INTO `reference` (
    `id`,               `referenceType`,
    `authors`,          `title`,
    `secondaryAuthors`, `secondaryTitle`,
    `tertiaryAuthors`,  `tertiaryTitle`,
    `year`,             `volume`,
    `number`,           `pages`,
    `section`,          `edition`,
    `place`,            `publisher`,
    `isbn`
) VALUES (
    %s, %u,
    %s, %s,
    %s, %s,
    %s, %s,
    %s, %s,
    %s, %s,
    %s, %s,
    %s, %s,
    %s
);\n", static::e($this->id),                $this->referenceType,
       static::e($this->authors),           static::e($this->title),
       static::e($this->secondaryAuthors),  static::e($this->secondaryTitle),
       static::e($this->tertiaryAuthors),   static::e($this->tertiaryTitle),
       static::e($this->year),              static::e($this->volume),
       static::e($this->number),            static::e($this->pages),
       static::e($this->section),           static::e($this->edition),
       static::e($this->place),             static::e($this->publisher),
       static::e($this->isbn));

        return $a;
    }


    /**
     * Return a MySQL DROP TABLE statement for `reference`
     *
     * @return string
     */
    public static function getDropSql()
    {
        return "DROP TABLE IF EXISTS `reference`;\n\n";
    }


    /**
     * Returns a MySQL CREATE TABLE statement for `reference`
     *
     * @return string
     */
    public static function getCreateSql()
    {
        return "CREATE TABLE IF NOT EXISTS `reference` (
    `id` varchar(48) NOT NULL,
    `referenceType` smallint(5) unsigned NOT NULL DEFAULT '0',
    `authors` mediumtext NOT NULL,
    `year` mediumtext NOT NULL,
    `title` mediumtext NOT NULL,
    `secondaryTitle` mediumtext NOT NULL,
    `secondaryAuthors` mediumtext NOT NULL,
    `tertiaryTitle` mediumtext NOT NULL,
    `tertiaryAuthors` mediumtext NOT NULL,
    `volume` mediumtext NOT NULL,
    `number` mediumtext NOT NULL,
    `pages` mediumtext NOT NULL,
    `section` mediumtext NOT NULL,
    `edition` mediumtext NOT NULL,
    `place` mediumtext NOT NULL,
    `publisher` mediumtext NOT NULL,
    `isbn` mediumtext NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;\n\n";
    }
}
