<?php

use \Mett\DistributionUnits;

class DistributionUnitsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group distributionPatriaIgnota
     * @group distributionUnit
     */
    public function testCasePatriaIgnota_1()
    {
        $distributionString = 'Patria ignota';
        $distributionString = DistributionUnits::filterPatriaIgnota($distributionString);

        $this->assertEquals('PIG', $distributionString);
    }

    /**
     * @group distributionPatriaIgnota
     * @group distributionUnit
     */
    public function testCasePatriaIgnota_2()
    {
        $distributionString = '"Patria ignota"';
        $distributionString = DistributionUnits::filterPatriaIgnota($distributionString);

        $this->assertEquals('PIG', $distributionString);
    }

    /**
     * @group distributionPatriaIgnota
     * @group distributionUnit
     */
    public function testCasePatriaIgnota_3()
    {
        $distributionString = 'distr. unknown';
        $distributionString = DistributionUnits::filterPatriaIgnota($distributionString);

        $this->assertEquals('PIG', $distributionString);
    }

    /**
     * Special case: E: "Patria ignota" means patria unknown but in Europe.
     * Must result in a "E: PIG".
     *
     * @group distributionPatriaIgnota
     * @group distributionUnit
     */
    public function testCasePatriaIgnota_4()
    {
        $distributionString = 'E: "Patria ignota"';
        $distributionString = DistributionUnits::filterPatriaIgnota($distributionString);

        $this->assertEquals('E: "Patria ignota"', $distributionString);
    }

    /**
     * @group distributionTokenize
     * @group distributionUnit
     */
    public function testCaseSplit_0()
    {
        $distributionString = 'E: "Patria ignota"';
        $tokens             = DistributionUnits::split($distributionString);

        $this->assertSame(['E:', 'Patria ignota'], $tokens);
    }

    /**
     * @group distributionTokenize
     * @group distributionUnit
     */
    public function testCaseSplit_1()
    {
        $distributionString = 'PIG';
        $tokens             = DistributionUnits::split($distributionString);

        $this->assertSame(['PIG'], $tokens);
    }

    /**
     * @group distributionTokenize
     * @group distributionUnit
     */
    public function testCaseSplit_2()
    {
        $distributionString = 'Ei: FR SP Ni: MO Ai: ES JA QA TAI NAR';
        $tokens             = DistributionUnits::split($distributionString);

        $this->assertSame(['Ei:','FR','SP','Ni:','MO','Ai:','ES','JA','QA','TAI','NAR'], $tokens);
    }

    /**
     * @group distributionTokenize
     * @group distributionUnit
     */
    public function testCaseSplit_3()
    {
        $distributionString = 'E: CR FR IT (Sardegna) PT SP N: AG MO TU A: SA';
        $tokens             = DistributionUnits::split($distributionString);

        $this->assertSame(['E:','CR','FR','IT','(Sardegna)','PT','SP','N:','AG','MO','TU','A:', 'SA'], $tokens);
    }

    /**
     * @group distributionTokenize
     * @group distributionUnit
     */
    public function testCaseSplit_4()
    {
        $distributionString = 'N: CI (Gomera, La Palma)';
        $tokens             = DistributionUnits::split($distributionString);

        $this->assertSame(['N:','CI','(Gomera, La Palma)'], $tokens);
    }

    /**
     * @group distributionTokenize
     * @group distributionUnit
     */
    public function testCaseSplit_5()
    {
        $distributionString = 'E: "Caucasus" A: IN UZ';
        $tokens             = DistributionUnits::split($distributionString);

        $this->assertSame(['E:','Caucasus','A:','IN','UZ'], $tokens);
    }

    /**
     * @group distributionTokenize
     * @group distributionUnit
     */
    public function testCaseSplit_6()
    {
        $distributionString = 'E: "Caucasus (?)"';
        $tokens             = DistributionUnits::split($distributionString);

        $this->assertSame(['E:','Caucasus (?)'], $tokens);
    }

    /**
     * @group distributionTokenize
     * @group distributionUnit
     */
    public function testCaseSplit_7()
    {
        $distributionString = 'E: AU LU PL(?) SK SP SZ N: AG MO';
        $tokens             = DistributionUnits::split($distributionString);

        $this->assertSame(['E:','AU','LU','PL(?)','SK','SP','SZ','N:','AG','MO'], $tokens);
    }

    /**
     * @group distributionTokenize
     * @group distributionUnit
     */
    public function testCaseSplit_8()
    {
        $distributionString = 'E: PIG';
        $tokens             = DistributionUnits::split($distributionString);

        $this->assertSame(['E:','PIG'], $tokens);
    }

    /**
     * @group distributionTokenize
     * @group distributionUnit
     */
    public function testCaseTokenize_1()
    {
        $distributionString = 'E: "Caucasus (?)" PL(?) A: INi UZ N: CI (Gomera, La Palma) COS';
        $tokens             = DistributionUnits::tokenize($distributionString);

        $shouldbe = [
            0  => [
                'token'      => 'PAL',
                'text'       => null,
                'level'      => 0,
                'introduced' => false,
                'doubtful'   => false,
            ],
            1  => [
                'token'      => 'E',
                'text'       => null,
                'level'      => 1,
                'introduced' => false,
                'doubtful'   => false,
            ],
            2  => [
                'token'      => null,
                'text'       => 'Caucasus',
                'level'      => 2,
                'introduced' => false,
                'doubtful'   => true,
            ],
            3  => [
                'token'      => 'PL',
                'text'       => null,
                'level'      => 2,
                'introduced' => false,
                'doubtful'   => true,
            ],
            4  => [
                'token'      => 'PAL',
                'text'       => null,
                'level'      => 0,
                'introduced' => false,
                'doubtful'   => false,
            ],
            5  => [
                'token'      => 'A',
                'text'       => null,
                'level'      => 1,
                'introduced' => false,
                'doubtful'   => false,
            ],
            6  => [
                'token'      => 'IN',
                'text'       => null,
                'level'      => 2,
                'introduced' => true,
                'doubtful'   => false,
            ],
            7  => [
                'token'      => 'UZ',
                'text'       => null,
                'level'      => 2,
                'introduced' => false,
                'doubtful'   => false,
            ],
            8  => [
                'token'      => 'PAL',
                'text'       => null,
                'level'      => 0,
                'introduced' => false,
                'doubtful'   => false,
            ],
            9  => [
                'token'      => 'N',
                'text'       => null,
                'level'      => 1,
                'introduced' => false,
                'doubtful'   => false,
            ],
            10 => [
                'token'      => 'CI',
                'text'       => null,
                'level'      => 2,
                'introduced' => false,
                'doubtful'   => false,
            ],
            11 => [
                'token'      => null,
                'text'       => 'Gomera',
                'level'      => 3,
                'introduced' => false,
                'doubtful'   => false,
            ],
            12 => [
                'token'      => null,
                'text'       => 'La Palma',
                'level'      => 3,
                'introduced' => false,
                'doubtful'   => false,
            ],
            13 => [
                'token'      => 'COS',
                'text'       => null,
                'level'      => 0,
                'introduced' => false,
                'doubtful'   => false,
            ],
        ];

        $this->assertSame($shouldbe, $tokens);
    }

    /**
     * @group distributionTokenize
     * @group distributionUnit
     */
    public function testCaseTokenize_2()
    {
        $distributionString = 'E: "Caucasus"';
        $tokens             = DistributionUnits::tokenize($distributionString);

        $expected = [
            0  => [
                'token'      => 'PAL',
                'text'       => null,
                'level'      => 0,
                'introduced' => false,
                'doubtful'   => false,
            ],
            1  => [
                'token'      => 'E',
                'text'       => null,
                'level'      => 1,
                'introduced' => false,
                'doubtful'   => false,
            ],
            2  => [
                'token'      => null,
                'text'       => 'Caucasus',
                'level'      => 2,
                'introduced' => false,
                'doubtful'   => false,
            ]
        ];

        $this->assertSame($expected, $tokens);
    }


    /**
     * @group distributionUnit
     */
    public function testCaseUnitsPatriaIgnota_1()
    {
        $taxonId         = 'blablubbid';
        $recordSource    = 'lsbd8:356';
        $created         = "2015-08-27 12:14:45";
        $createdByUserId = 2;
        $reference       = [
            'taxonId'         => $taxonId,
            'recordSource'    => $recordSource,
            'created'         => $created,
            'createdByUserId' => $createdByUserId
        ];
        $distributionString = 'Patria ignota';

        $units              = new DistributionUnits($reference, $distributionString);

        $unit = $units->distributions[0];
        $this->assertEquals($unit->level0, 'PIG');
        $this->assertFalse($unit->level0introduced);
        $this->assertFalse($unit->level0doubtful);

        $this->assertSame($unit->level1, null);
        $this->assertFalse($unit->level1introduced);
        $this->assertFalse($unit->level1doubtful);

        $this->assertSame($unit->level2, null);
        $this->assertSame($unit->level2text, null);
        $this->assertFalse($unit->level2introduced);
        $this->assertFalse($unit->level2doubtful);

        $this->assertSame($unit->level3, null);
        $this->assertSame($unit->level3text, null);
        $this->assertFalse($unit->level1introduced);
        $this->assertFalse($unit->level1doubtful);

        $this->assertSame($unit->taxonId, $taxonId);
        $this->assertSame($unit->recordSource, $recordSource);
        $this->assertSame($unit->created, $created);
        $this->assertSame($unit->createdByUserId, $createdByUserId);
    }

    /**
     * @group distributionUnit
     */
    public function testCaseUnitsPatriaIgnota_2()
    {
        $taxonId         = 'blablubbid';
        $recordSource    = 'lsbd8:356';
        $created         = "2015-08-27 12:14:45";
        $createdByUserId = 2;
        $reference       = [
            'taxonId'         => $taxonId,
            'recordSource'    => $recordSource,
            'created'         => $created,
            'createdByUserId' => $createdByUserId
        ];
        $distributionString = 'E: "Patria ignota"';

        $units              = new DistributionUnits($reference, $distributionString);

        $unit = $units->distributions[0];
        $this->assertEquals($unit->level0, 'PAL');
        $this->assertFalse($unit->level0introduced);
        $this->assertFalse($unit->level0doubtful);

        $this->assertSame($unit->level1, 'E');
        $this->assertFalse($unit->level1introduced);
        $this->assertFalse($unit->level1doubtful);

        $this->assertSame($unit->level2, null);
        $this->assertSame($unit->level2text, 'Patria ignota');
        $this->assertFalse($unit->level2introduced);
        $this->assertFalse($unit->level2doubtful);

        $this->assertSame($unit->level3, null);
        $this->assertSame($unit->level3text, null);
        $this->assertFalse($unit->level1introduced);
        $this->assertFalse($unit->level1doubtful);

        $this->assertSame($unit->taxonId, $taxonId);
        $this->assertSame($unit->recordSource, $recordSource);
        $this->assertSame($unit->created, $created);
        $this->assertSame($unit->createdByUserId, $createdByUserId);
    }


    /**
     * @group distributionUnit
     */
    public function testCaseUnitsNotSimpleExample()
    {
        $taxonId         = 'blablubbid';
        $recordSource    = 'lsbd8:356';
        $created         = "2015-08-27 12:14:45";
        $createdByUserId = 2;
        $reference       = [
            'taxonId'         => $taxonId,
            'recordSource'    => $recordSource,
            'created'         => $created,
            'createdByUserId' => $createdByUserId
        ];

        $distributionString = 'E: "Caucasus (?)" PL(?) A: INi UZ N: CI (Gomera, La Palma) COS';
        $units              = new DistributionUnits($reference, $distributionString);

        ## [PAL] E: "Caucasus (?)"
        $unit = $units->distributions[0];
        $this->assertEquals($unit->level0, 'PAL');
        $this->assertFalse($unit->level0introduced);
        $this->assertFalse($unit->level0doubtful);

        $this->assertSame($unit->level1, 'E');
        $this->assertFalse($unit->level1introduced);
        $this->assertFalse($unit->level1doubtful);

        $this->assertSame($unit->level2, null);
        $this->assertSame($unit->level2text, 'Caucasus');
        $this->assertFalse($unit->level2introduced);
        $this->assertTrue($unit->level2doubtful);

        $this->assertSame($unit->level3, null);
        $this->assertSame($unit->level3text, null);
        $this->assertFalse($unit->level1introduced);
        $this->assertFalse($unit->level1doubtful);

        $this->assertSame($unit->taxonId, $taxonId);
        $this->assertSame($unit->recordSource, $recordSource);
        $this->assertSame($unit->created, $created);
        $this->assertSame($unit->createdByUserId, $createdByUserId);

        # [PAL E:] PL(?)
        $unit = $units->distributions[1];
        $this->assertEquals($unit->level0, 'PAL');
        $this->assertFalse($unit->level0introduced);
        $this->assertFalse($unit->level0doubtful);

        $this->assertSame($unit->level1, 'E');
        $this->assertFalse($unit->level1introduced);
        $this->assertFalse($unit->level1doubtful);

        $this->assertSame($unit->level2, 'PL');
        $this->assertSame($unit->level2text, null);
        $this->assertFalse($unit->level2introduced);
        $this->assertTrue($unit->level2doubtful);

        $this->assertSame($unit->level3, null);
        $this->assertSame($unit->level3text, null);
        $this->assertFalse($unit->level1introduced);
        $this->assertFalse($unit->level1doubtful);

        # [PAL] A: INi
        $unit = $units->distributions[2];
        $this->assertEquals($unit->level0, 'PAL');
        $this->assertFalse($unit->level0introduced);
        $this->assertFalse($unit->level0doubtful);

        $this->assertSame($unit->level1, 'A');
        $this->assertFalse($unit->level1introduced);
        $this->assertFalse($unit->level1doubtful);

        $this->assertSame($unit->level2, 'IN');
        $this->assertSame($unit->level2text, null);
        $this->assertTrue($unit->level2introduced);
        $this->assertFalse($unit->level2doubtful);

        $this->assertSame($unit->level3, null);
        $this->assertSame($unit->level3text, null);
        $this->assertFalse($unit->level1introduced);
        $this->assertFalse($unit->level1doubtful);

        # [PAL A:] UZ
        $unit = $units->distributions[3];
        $this->assertEquals($unit->level0, 'PAL');
        $this->assertFalse($unit->level0introduced);
        $this->assertFalse($unit->level0doubtful);

        $this->assertSame($unit->level1, 'A');
        $this->assertFalse($unit->level1introduced);
        $this->assertFalse($unit->level1doubtful);

        $this->assertSame($unit->level2, 'UZ');
        $this->assertSame($unit->level2text, null);
        $this->assertFalse($unit->level2introduced);
        $this->assertFalse($unit->level2doubtful);

        $this->assertSame($unit->level3, null);
        $this->assertSame($unit->level3text, null);
        $this->assertFalse($unit->level1introduced);
        $this->assertFalse($unit->level1doubtful);

        # [PAL] N: CI (Gomera)
        $unit = $units->distributions[4];
        $this->assertEquals($unit->level0, 'PAL');
        $this->assertFalse($unit->level0introduced);
        $this->assertFalse($unit->level0doubtful);

        $this->assertSame($unit->level1, 'N');
        $this->assertFalse($unit->level1introduced);
        $this->assertFalse($unit->level1doubtful);

        $this->assertSame($unit->level2, 'CI');
        $this->assertSame($unit->level2text, null);
        $this->assertFalse($unit->level2introduced);
        $this->assertFalse($unit->level2doubtful);

        $this->assertSame($unit->level3, null);
        $this->assertSame($unit->level3text, 'Gomera');
        $this->assertFalse($unit->level1introduced);
        $this->assertFalse($unit->level1doubtful);

        # [PAL N: CI] (La Palma)
        $unit = $units->distributions[5];
        $this->assertEquals($unit->level0, 'PAL');
        $this->assertFalse($unit->level0introduced);
        $this->assertFalse($unit->level0doubtful);

        $this->assertSame($unit->level1, 'N');
        $this->assertFalse($unit->level1introduced);
        $this->assertFalse($unit->level1doubtful);

        $this->assertSame($unit->level2, 'CI');
        $this->assertSame($unit->level2text, null);
        $this->assertFalse($unit->level2introduced);
        $this->assertFalse($unit->level2doubtful);

        $this->assertSame($unit->level3, null);
        $this->assertSame($unit->level3text, 'La Palma');
        $this->assertFalse($unit->level1introduced);
        $this->assertFalse($unit->level1doubtful);

        # COS
        $unit = $units->distributions[6];
        $this->assertEquals($unit->level0, 'COS');
        $this->assertFalse($unit->level0introduced);
        $this->assertFalse($unit->level0doubtful);

        $this->assertSame($unit->level1, null);
        $this->assertFalse($unit->level1introduced);
        $this->assertFalse($unit->level1doubtful);

        $this->assertSame($unit->level2, null);
        $this->assertSame($unit->level2text, null);
        $this->assertFalse($unit->level2introduced);
        $this->assertFalse($unit->level2doubtful);

        $this->assertSame($unit->level3, null);
        $this->assertSame($unit->level3text, null);
        $this->assertFalse($unit->level1introduced);
        $this->assertFalse($unit->level1doubtful);
    }
}