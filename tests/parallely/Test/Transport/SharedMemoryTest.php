<?php
/**
 * Test class for parallely_Transport_SharedMemory.
 * Generated by PHPUnit on 2012-03-24 at 14:59:34.
 */
namespace parallely\Test\Transport;

class SharedMemoryTest extends \parallely\Test\Transport\TestAbstract {

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp() {
        $this->_sTestClass = '\\parallely\\Transport\\SharedMemory';
        $this->_oOptions = new \stdClass();
        $this->_oOptions->path = '/tmp';

        parent::setUp();
    }
}
