<?php

use \Mett\Reference;

class ReferenceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group reference
     */
    public function testE()
    {
        $this->assertSame('NULL', Reference::e(0));
        $this->assertSame('NULL', Reference::e(null));
        $this->assertSame('NULL', Reference::e(false));
        $this->assertSame("'1'", Reference::e(1));
        $this->assertSame("'blablubb'", Reference::e('blablubb'));
        $this->assertSame("'blablubbü'", Reference::e('blablubbü'));
    }

    /**
     * @group reference
     */
    public function testDropSql()
    {
        $expected = "DROP TABLE IF EXISTS `reference`;\n\n";

        $this->assertEquals($expected, Reference::getDropSql());
    }

    /**
     * @group reference
     */
    public function testCreateSql()
    {
        $expected = "CREATE TABLE IF NOT EXISTS `reference` (
    `id` varchar(48) NOT NULL,
    `referenceTypeId` smallint(5) unsigned NOT NULL DEFAULT '0',
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

";

        $this->assertEquals($expected, Reference::getCreateSql());
    }

    /**
     * @group export
     * @group reference
     */
    public function testInsertSql()
    {
        $created         = "2015-08-27 12:14:45";
        $createdByUserId = 2;
        $reference       = [
            'id'               => 'blablubbid',
            'referenceTypeId'  => 7,
            'authors'          => "Trýzna, Miloš\nValentine, Barry D.",
            'year'             => "2011",
            'title'            => "Anthribinae",
            'secondaryTitle'   => "Catalogue of Palearctic Coleoptera",
            'secondaryAuthors' => "Löbl, Ivan\nSmatana, Aleš",
            'tertiaryTitle'    => null,
            'tertiaryAuthors'  => null,
            'volume'           => "7",
            'number'           => null,
            'pages'            => "373",
            'section'          => "90-104",
            'edition'          => null,
            'place'            => "Stenstrup",
            'publisher'        => "Apolo Books",
            'isbn'             => "978-87-88757-93-4",
        ];

        $unit     = new Reference($reference);
        $expected = "INSERT IGNORE INTO `reference` (
    `id`,               `referenceTypeId`,
    `authors`,          `title`,
    `secondaryAuthors`, `secondaryTitle`,
    `tertiaryAuthors`,  `tertiaryTitle`,
    `year`,             `volume`,
    `number`,           `pages`,
    `section`,          `edition`,
    `place`,            `publisher`,
    `isbn`
) VALUES (
    'blablubbid', 7,
    'Trýzna, Miloš\nValentine, Barry D.', 'Anthribinae',
    'Löbl, Ivan\nSmatana, Aleš', 'Catalogue of Palearctic Coleoptera',
    NULL, NULL,
    '2011', '7',
    NULL, '373',
    '90-104', NULL,
    'Stenstrup', 'Apolo Books',
    '978-87-88757-93-4'
);\n";

        $this->assertEquals($expected, $unit->getInsertSql());
    }
}