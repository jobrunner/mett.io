<?php

use \Mett\TaxonReference;

class TaxonReferenceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group taxonreference
     */
    public function testE()
    {
        $this->assertSame('NULL', TaxonReference::e(0));
        $this->assertSame('NULL', TaxonReference::e(null));
        $this->assertSame('NULL', TaxonReference::e(false));
        $this->assertSame("'1'", TaxonReference::e(1));
        $this->assertSame("'blablubb'", TaxonReference::e('blablubb'));
        $this->assertSame("'blablubbü'", TaxonReference::e('blablubbü'));
    }

    /**
     * @group taxonreference
     */
    public function testDropSql()
    {
        $expected = "DROP TABLE IF EXISTS `taxon_reference`;\n\n";

        $this->assertEquals($expected, TaxonReference::getDropSql());
    }

    /**
     * @group taxonreference
     */
    public function testCreateSql()
    {
        $expected = "CREATE TABLE IF NOT EXISTS `taxon_reference` (
    `taxonId` VARCHAR(48) NOT NULL,
    `referenceId` VARCHAR(48) NOT NULL,
    PRIMARY KEY (`taxonId`, `referenceId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

";

        $this->assertEquals($expected, TaxonReference::getCreateSql());
    }

    /**
     * @group export
     * @group taxonreference
     */
    public function testInsertSql()
    {
        $reference       = [
            'taxonId'          => 'blablubbid-taxon',
            'referenceId'      => 'blablubbbla-reference',
        ];

        $unit     = new TaxonReference($reference);
        $expected = "INSERT IGNORE INTO `taxon_reference` (
    `taxonId`, `referenceId`
) VALUES (
    'blablubbid-taxon', 'blablubbbla-reference'
);\n";

        $this->assertEquals($expected, $unit->getInsertSql());
    }
}