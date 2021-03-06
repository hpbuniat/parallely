<?php
/**
 * Test class for parallely_Transport_File.
 * Generated by PHPUnit on 2012-03-24 at 14:59:34.
 */
namespace parallely\Test\Transport;

class XcacheTest extends \PHPUnit_Framework_TestCase {

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp() {

    }

    /**
     * Xcache is not available via cli
     */
    public function testCli() {
        $this->setExpectedException('\\parallely\\Exception', \parallely\Exception::SETUP_ERROR);

        $oXcache = new \parallely\Transport\Xcache();
        $this->assertInstanceOf('\\parallely\\TransportInterface', $oXcache);
        $oXcache->read(md5(time()));
    }
}
