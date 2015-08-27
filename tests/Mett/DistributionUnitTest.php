<?php

use \Mett\DistributionUnit;

class DistributionUnitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group distributionUnit
     */
    public function testE()
    {
        $this->assertSame('NULL', DistributionUnit::e(0));
        $this->assertSame('NULL', DistributionUnit::e(null));
        $this->assertSame('NULL', DistributionUnit::e(false));
        $this->assertSame("'1'", DistributionUnit::e(1));
        $this->assertSame("'blablubb'", DistributionUnit::e('blablubb'));
        $this->assertSame("'blablubbü'", DistributionUnit::e('blablubbü'));
    }

    /**
     * @group distributionUnit
     */
    public function testDropSql()
    {
        $expected = "DROP TABLE IF EXISTS `distribution_unit`;\n\n";

        $this->assertEquals($expected, DistributionUnit::getDropSql());
    }

    /**
     * @group distributionUnit
     */
    public function testCreateSql()
    {
        $expected = "CREATE TABLE `distribution_unit` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

";

        $this->assertEquals($expected, DistributionUnit::getCreateSql());
    }

    /**
     * @group export
     * @group distributionUnit
     */
    public function testInsertSql()
    {
        $taxonId         = 'blablubbid';
        $recordSource    = 'lsbd8:356';
        $created         = "2015-08-27 12:14:45";
        $createdByUserId = 2;
        $reference       = [
            'taxonId'         => $taxonId,
            'recordSource'    => $recordSource,
            'created'         => $created,
            'createdByUserId' => $createdByUserId,
            'level0' => 'PAL',
            'level0introduced' => false,
            'level0doubtful' => false,
            'level1' => 'E',
            'level1introduced' => false,
            'level1doubtful' => false,
            'level2' => null,
            'level2text' => 'Caucasus',
            'level2introduced' => false,
            'level2doubtful' => true,
        ];

        $unit     = new DistributionUnit($reference);
        $expected = "INSERT IGNORE INTO `distribution_unit` (
id,
taxonId, recordSource, created, createdByUserId,
level0, level0introduced, level0doubtful,
level1, level1introduced, level1doubtful,
level2, level2introduced, level2doubtful, level2text,
level3, level3introduced, level3doubtful, level3text) VALUES (
null,
'blablubbid', 'lsbd8:356', '2015-08-27 12:14:45', 2,
'PAL', 0, 0,
'E', 0, 0,
NULL, 0, 1, 'Caucasus',
NULL, 0, 0, NULL);
";

        $this->assertEquals($expected, $unit->getInsertSql());
    }



}