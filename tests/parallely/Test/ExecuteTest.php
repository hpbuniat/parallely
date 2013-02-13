<?php
/**
 * Test class for parallely_Util_Parallel.
 * Generated by PHPUnit on 2011-10-23 at 17:48:39.
 */
namespace parallely\Test;

class ExecuteTest extends \PHPUnit_Framework_TestCase {

    /**
     * The object to test with
     *
     * @var string
     */
    const TEST_MOCK = '\\parallely\\Transport\\File';

    /**
     * @var \parallely\Execute
     */
    protected $_object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp() {
        $aStack = array();
        for ($i = 0; $i < 3; $i++) {
            $oMock = $this->getMock(self::TEST_MOCK);
            $oMock->expects($this->any())->method('free')->will($this->returnSelf());
            $aStack[] = $oMock;
        }

        $oTransport = $this->getMock(self::TEST_MOCK);
        $oTransport->expects($this->at(0))->method('read')->will($this->returnValue(false));
        $oTransport->expects($this->any())->method('read')->will($this->returnValue(gzcompress(serialize($oTransport))));
        $oTransport->expects($this->any())->method('write')->will($this->returnSelf());
        $oTransport->expects($this->any())->method('free')->will($this->returnSelf());

        $this->_object = new \parallely\Execute($aStack, $oTransport);
    }

    /**
     * Test the complete workflow
     */
    public function testWorkflow() {
        $this->assertInstanceOf('\\parallely\\Execute', $this->_object->threads());
        $this->assertEquals(\parallely\Execute::THREADS, $this->_object->getThreads());
        $this->assertInstanceOf('\\parallely\\Execute', $this->_object->threads(2));
        $this->assertEquals(2, $this->_object->getThreads());

        $this->assertInstanceOf('\\parallely\\Execute', $this->_object->run(array(
            'free'
        )));

        $aStack = $this->_object->get();
        $this->assertInternalType('array', $aStack);
        $this->assertEquals(3, count($aStack));
        foreach ($aStack as $oItem) {
            $this->assertInstanceOf(self::TEST_MOCK, $oItem);
        }
    }

    /**
     * Test with a single thread
     */
    public function testSingle() {
        $this->_object->getTransport()->read(md5(time()));

        $this->assertInstanceOf('\\parallely\\Execute', $this->_object->threads(1));
        $this->assertEquals(1, $this->_object->getThreads());

        $this->assertInstanceOf('\\parallely\\Execute', $this->_object->run(array(
            'free'
        )));

        $aStack = $this->_object->get();
        $this->assertInternalType('array', $aStack);
        $this->assertEquals(3, count($aStack));
        foreach ($aStack as $oItem) {
            $this->assertInstanceOf(self::TEST_MOCK, $oItem);
        }
    }

    /**
     * Test setting a transport
     */
    public function testSetTransport() {
        $this->_object->getTransport()->read(md5(time()));

        $oMock = $this->getMock(self::TEST_MOCK);
        $this->assertInstanceOf('\\parallely\\Execute', $this->_object->setTransport($oMock));
        $this->assertEquals($oMock, $this->_object->getTransport());
    }
}
