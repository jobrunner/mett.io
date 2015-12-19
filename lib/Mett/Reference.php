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
    `secondaryAuthor` mediumtext NOT NULL,
    `tertiaryTitle` mediumtext NOT NULL,
    `tertiaryAuthor` mediumtext NOT NULL,
    `volume` mediumtext NOT NULL,
    `number` mediumtext NOT NULL,
    `pages` mediumtext NOT NULL,
    `section` mediumtext NOT NULL,
    `edition` mediumtext NOT NULL,
    `placePublished` mediumtext NOT NULL,
    `publisher` mediumtext NOT NULL,
    `isbn` mediumtext NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;\n\n";
    }
}



/*

  `text_styles` mediumtext NOT NULL,
  `keywords` mediumtext NOT NULL,
  `type_of_work` mediumtext NOT NULL,
  `date` mediumtext NOT NULL,
  `abstract` mediumtext NOT NULL,
  `label` mediumtext NOT NULL,
  `url` mediumtext NOT NULL,
  `notes` mediumtext NOT NULL,
  `custom_1` mediumtext NOT NULL,
  `custom_2` mediumtext NOT NULL,
  `custom_3` mediumtext NOT NULL,
  `custom_4` mediumtext NOT NULL,
  `alternate_title` mediumtext NOT NULL,
  `call_number` mediumtext NOT NULL,
  `short_title` mediumtext NOT NULL,
  `custom_5` mediumtext NOT NULL,
  `custom_6` mediumtext NOT NULL,
  `original_publication` mediumtext NOT NULL,
  `reprint_edition` mediumtext NOT NULL,
  `reviewed_item` mediumtext NOT NULL,
  `author_address` mediumtext NOT NULL,
  `image` mediumtext NOT NULL,
  `caption` mediumtext NOT NULL,
  `custom_7` mediumtext NOT NULL,
  `electronic_resource_number` mediumtext NOT NULL,
  `link_to_pdf` mediumtext NOT NULL,
  `translated_author` mediumtext NOT NULL,
  `translated_title` mediumtext NOT NULL,
  `name_of_database` mediumtext NOT NULL,
  `database_provider` mediumtext NOT NULL,
  `research_notes` mediumtext NOT NULL,
  `last_modified_date` mediumtext NOT NULL
  `accession_number` mediumtext NOT NULL,
  `access_date` mediumtext NOT NULL,
  `language` mediumtext NOT NULL,
  `number_of_volumes` mediumtext NOT NULL,
  `subsidiary_author` mediumtext NOT NULL,
  `edition` mediumtext NOT NULL,


CREATE TABLE IF NOT EXISTS `reference` (
  `id` int(10) unsigned NOT NULL,
  `referenceType` smallint(5) unsigned NOT NULL DEFAULT '0',
  `authors` mediumtext NOT NULL,
  `year` mediumtext NOT NULL,
  `title` mediumtext NOT NULL,
  `secondaryTitle` mediumtext NOT NULL,
  `secondaryAuthor` mediumtext NOT NULL,
  `tertiaryTitle` mediumtext NOT NULL,
  `tertiaryAuthor` mediumtext NOT NULL,
  `volume` mediumtext NOT NULL,
  `number` mediumtext NOT NULL,
  `pages` mediumtext NOT NULL,
  `section` mediumtext NOT NULL,
  `placePublished` mediumtext NOT NULL,
  `publisher` mediumtext NOT NULL,
  `isbn` mediumtext NOT NULL,

) ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/