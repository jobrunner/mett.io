<?php
class UUIDTest extends PHPUnit_Framework_TestCase
{
    public $uuidFormat;
    public $invalidUUID;

    public function setUp()
    {
        $this->uuidFormat  = "#^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$#";
        $this->invalidUUID = '1546058f-5a25-4334-85ae-e68f2a44bbaz';
    }

    public function testNsUrlConstant()
    {
        $this->assertRegExp($this->uuidFormat, \Mett\UUID::NS_URL);
    }

    public function testNsDnsConstant()
    {
        $this->assertRegExp($this->uuidFormat, \Mett\UUID::NS_DNS);
    }

    public function testNsIsoOidConstant()
    {
        $this->assertRegExp($this->uuidFormat, \Mett\UUID::NS_ISO_OID);
    }

    public function testNsX500DnConstant()
    {
        $this->assertRegExp($this->uuidFormat, \Mett\UUID::NS_X500_DN);
    }

    public function testV3()
    {
        $result = \Mett\UUID::v3('1546058f-5a25-4334-85ae-e68f2a44bbaf', uniqid('uuid test', true));
        $this->assertRegExp($this->uuidFormat, $result);
    }

    public function testV3InvalidData()
    {
        $result = \Mett\UUID::v3($this->invalidUUID, uniqid('uuid test', true));
        $this->assertFalse($result);
    }

    public function testV4()
    {
        $result = \Mett\UUID::v4();
        $this->assertRegExp($this->uuidFormat, $result);
    }

    public function testV5NsDns()
    {
        $result = \Mett\UUID::v5(\Mett\UUID::NS_DNS, 'mett.io');
        $this->assertRegExp($this->uuidFormat, $result);
    }

    public function testV5NsUrl()
    {
        $result = \Mett\UUID::v5(\Mett\UUID::NS_URL, 'http://mett.io/');
        $this->assertRegExp($this->uuidFormat, $result);
    }

    public function testV5NsIsoOid()
    {
        $result = \Mett\UUID::v5(\Mett\UUID::NS_ISO_OID, 'OID-description');
        $this->assertRegExp($this->uuidFormat, $result);
    }

    public function testV5NsX500Dn()
    {
        $result = \Mett\UUID::v5(\Mett\UUID::NS_X500_DN, 'X500-description');
        $this->assertRegExp($this->uuidFormat, $result);
    }

    public function testV5InvalidData()
    {
        $result = \Mett\UUID::v5($this->invalidUUID, 'Invalid-description');
        $this->assertFalse($result);
    }

    public function testV4Binary16LengthOf16Bytes()
    {
        $binaryUUID = \Mett\UUID::v4binary16();
        $expected = 16;

        $this->assertEquals($expected, strlen($binaryUUID));
    }

    public function testHexUUIDFromBinary16()
    {
        $binaryUUID = pack("H*", "4C80BE7D4F5B461CBFE66F694BEFF7A3");

        $result   = \Mett\UUID::hexUUIDFromBinary16($binaryUUID);
        $expected = "4C80BE7D-4F5B-461C-BFE6-6F694BEFF7A3";

        $this->assertEquals($expected, $result);
    }
}
