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
        TaxonReference::$tableTaxonReference = 'taxon_reference_e42';
        $expected = "DROP TABLE IF EXISTS `taxon_reference_e42`;\n\n";

        $this->assertEquals($expected, TaxonReference::getDropSql());
    }

    /**
     * @group taxonreference
     */
    public function testCreateSql()
    {
        TaxonReference::$tableTaxonReference = 'taxon_reference_e42';
        $expected = "CREATE TABLE IF NOT EXISTS `taxon_reference_e42` (
    `taxonId` VARCHAR(48) NOT NULL,
    `referenceId` VARCHAR(48) NOT NULL,
    `referenceType` TINYINT(4) NOT NULL,
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
            'referenceType'    => 23,
        ];

        TaxonReference::$tableTaxonReference = 'taxon_reference_e42';
        $unit     = new TaxonReference($reference);
        $expected = "INSERT IGNORE INTO `taxon_reference_e42` (
    `taxonId`, `referenceId`, `referenceType`
) VALUES (
    'blablubbid-taxon', 'blablubbbla-reference', 23
);\n";

        $this->assertEquals($expected, $unit->getInsertSql());
    }


    /**
     * @group export
     * @group taxonreference
     */
    public function testInsertSqlZero()
    {
        $reference       = [
            'taxonId'          => 'blablubbid-taxon',
            'referenceId'      => 'blablubbbla-reference',
        ];

        TaxonReference::$tableTaxonReference = 'taxon_reference_e42';
        $unit     = new TaxonReference($reference);
        $expected = "INSERT IGNORE INTO `taxon_reference_e42` (
    `taxonId`, `referenceId`, `referenceType`
) VALUES (
    'blablubbid-taxon', 'blablubbbla-reference', 0
);\n";

        $this->assertEquals($expected, $unit->getInsertSql());
    }

}