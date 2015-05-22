<?php
use \Mett\IdGenerator;
use \Mett\Rand\MockGenerator;

class IdGeneratorTest extends PHPUnit_Framework_TestCase
{
    protected $timestamp;
    protected $isoDate;
    protected $objectId;
    protected $decimalObjectId;

    public function setUp()
    {
        $this->timestamp       = 1432145609;
        $this->isoDate         = "2015-05-20T18:13:29Z";
        $this->objectId        = "AzL8mxQL24n";
        $this->decimalObjectId = "9223372033991954121";
    }

    public function testGetTimestampFromObjectId()
    {
        $generator = new IdGenerator();
        $timestamp = $generator->getTimestamp($this->objectId);
        $shouldBe  = $this->timestamp;

        $this->assertEquals($shouldBe, $timestamp);
    }

    public function testGetIso8601DateFromObjectId()
    {
        $generator = new IdGenerator();
        $zeroDate  = $generator->getIso8601Date(0);

        $this->assertEquals($zeroDate, '1970-01-01T00:00:00+00:00');
    }

    public function testGetIso8601DateFromObjectId_2()
    {
        $generator    = new IdGenerator();
        $iso8601Date  = $generator->getIso8601Date($this->objectId);

        $this->assertEquals($iso8601Date, '2015-05-20T18:13:29+00:00');
    }

    public function testGetIsoCommonDateFromObjectId()
    {
        $generator    = new IdGenerator();
        $iso8601Date  = $generator->getIsoCommonDate($this->objectId);

        $this->assertEquals($iso8601Date, '2015-05-20T18:13:29Z');
    }

    public function testObjectIdDecimalFromObjectId()
    {
        $generator = new IdGenerator(new \Mett\Rand\MockGenerator());
        $decimal   = $generator->bigInt($this->objectId);

        $shouldBe  = $this->decimalObjectId;

        $this->assertEquals($shouldBe, $decimal);
    }

    public function testCreateObjectIdWithMock()
    {
        $generator = new Mett\IdGenerator(new MockGenerator());
        $objectId  = $generator->createObjectId($this->timestamp);
        $shouldBe  = $this->objectId;

        $this->assertEquals($shouldBe, $objectId);
    }

    public function testBigIntFromObjectId()
    {
        $generator        = new Mett\IdGenerator();
        $decimalObjectId  = $generator->bigInt($this->objectId);
        $shouldBe         = $this->decimalObjectId;

        $this->assertEquals($shouldBe, $decimalObjectId);
    }

    public function testNoOverflowForMySQLBigInt()
    {
        $generator        = new Mett\IdGenerator(new MockGenerator());
        $objectId         = $generator->createObjectId(0xFFFFFF);

        // current compiled/system capa of integer
        $phpInt           = bccomp($generator->bigInt($objectId), PHP_INT_MAX);
        $this->assertEquals($phpInt, -1);

        // MySQL singed big int
        $signedInt        = bccomp($generator->bigInt($objectId), '9223372036854775807');
        $this->assertEquals($signedInt, -1);

        // MySQL unsinged big int
        $unsignedInt      = bccomp($generator->bigInt($objectId), '18446744073709551615');
        $this->assertEquals($unsignedInt, -1);
    }
}