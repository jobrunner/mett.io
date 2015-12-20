<?php

use \Mett\DistributionReference;

class DistributionReferenceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group distributionreference
     */
    public function testE()
    {
        $this->assertSame('NULL', DistributionReference::e(0));
        $this->assertSame('NULL', DistributionReference::e(null));
        $this->assertSame('NULL', DistributionReference::e(false));
        $this->assertSame("'1'", DistributionReference::e(1));
        $this->assertSame("'blablubb'", DistributionReference::e('blablubb'));
        $this->assertSame("'blablubbü'", DistributionReference::e('blablubbü'));
    }

    /**
     * @group distributionreference
     */
    public function testDropSql()
    {
        $expected = "DROP TABLE IF EXISTS `distribution_reference`;\n\n";

        $this->assertEquals($expected, DistributionReference::getDropSql());
    }

    /**
     * @group distributionreference
     */
    public function testCreateSql()
    {
        $expected = "CREATE TABLE IF NOT EXISTS `distribution_reference` (
    `distributionId` VARCHAR(48) NOT NULL,
    `referenceId` VARCHAR(48) NOT NULL,
    PRIMARY KEY (`distributionId`, `referenceId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

";

        $this->assertEquals($expected, DistributionReference::getCreateSql());
    }

    /**
     * @group export
     * @group distributionreference
     */
    public function testInsertSql()
    {
        $reference       = [
            'distributionId'   => 'blablubbid-distribution',
            'referenceId'      => 'blablubbbla-reference',
        ];

        $unit     = new DistributionReference($reference);
        $expected = "INSERT IGNORE INTO `distribution_reference` (
    `distributionId`, `referenceId`
) VALUES (
    'blablubbid-distribution', 'blablubbbla-reference'
);\n";

        $this->assertEquals($expected, $unit->getInsertSql());
    }
}