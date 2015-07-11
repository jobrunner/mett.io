<?php
namespace Mett;

use \Mett\Coder\Base62;
use \Mett\Coder\CoderInterface;
use \Mett\Rand\GeneratorInterface;
use \Mett\Rand\DevUrandomGenerator as Generator;

/**
 * IdGenerator.
 *
 */
class IdGenerator
{
    /**
     * Instance of random generator
     *
     * @var Rand\GeneratorInterface
     */
    protected $_randGenerator;

    /**
     * Instance of coder
     * @var
     */
    protected $_coder;

    /**
     * Constructor.
     *
     * @param GeneratorInterface $randGenerator Random generator. Default generator is /dev/udevrandom.
     * @param CoderInterface     $coder         Coder for the ObjectId encoding. Default coder is Base62 coder.
     */
    public function __construct(GeneratorInterface $randGenerator = null,
                                    CoderInterface $coder = null)
    {
        if ($randGenerator == null) {
            $randGenerator = new Generator();
        }
        $this->_randGenerator = $randGenerator;

        if ($coder == null) {
            $coder = new Base62();
        }
        $this->_coder         = $coder;
    }

    /**
     * Creates an objectId that can be used in MySQL unsigned bigint fields.
     * Id is not unique but serves a relative good randomness for id's.
     * It consists of 4 Byte timestamp and 4 Bytes pseudo random number.
     *
     * @param   int     $prefix   Timestamp of object Id
     *
     * @return  string
     */
    public function createObjectId($prefix = null)
    {
        if ($prefix == null) {
            $prefix = time();
        }

        $random    = $this->_randGenerator->rand(0, 0x7FFFFFFF);
        $decimalId = bcadd(bcmul($random, bcpow(2, 32)), $prefix);

        return $this->_coder->encode($decimalId);
    }

    /**
     * Returns the unsinged 64 bit integer representation as string.
     * E.g., this representation can be stored in MySQLs UNSIGNED BIGINT field.
     *
     * @param   String    $objectId   The ObjectId
     *
     * @return  String
     */
    public function bigInt($objectId)
    {
        return $this->_coder->decode($objectId);
    }

    public function getTimestamp($objectId)
    {
        $decimal = (int)$this->bigInt($objectId);

        return ($decimal & 0xFFFFFFFF);
    }

    /**
     * Returns the implicit creation date of the objectId in ISO 8601 Date.
     *
     * @param   String    $objectId   The ObjectId
     *
     * @return  string
     */
    public function getIso8601Date($objectId)
    {
        return gmdate("c", $this->getTimestamp($objectId));
    }

    /**
     * Returns the implicit creation date of the objectId in modified (always UTC) ISO 8601 Date.
     * Because offset of timezone is always zero, it's common to replace offset with literal Z.
     *
     * @param   String    $objectId   The ObjectId
     *
     * @return  string
     */
    public function getIsoCommonDate($objectId)
    {
        return substr($this->getIso8601Date($objectId), 0, 19) . "Z";
    }
}
