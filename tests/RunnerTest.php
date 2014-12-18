<?php

namespace DevNanny\Connector;

use DevNanny\Connector\Interfaces\CollectionInterface;
use DevNanny\Connector\Interfaces\ConnectorInterface;

/**
 * @coversDefaultClass DevNanny\Connector\Runner
 * @covers ::<!public>
 * @covers ::__construct
 */
class RunnerTest extends \PHPUnit_Framework_TestCase
{
    ////////////////////////////////// FIXTURES \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var Runner */
    private $runner;
    /** @var CollectionInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $mockCollection;
    /** @var \ArrayIterator */
    private $iterator;

    protected function setUp()
    {
        $mockCollection = $this->getMockCollection();

        $this->mockCollection = $mockCollection;
        $this->runner = new Runner($this->mockCollection);
    }
    /////////////////////////////////// TESTS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     */
    final public function testRunnerShouldBeGivenCollectionWhenInstantiated()
    {
        $this->setExpectedExceptionRegExp(
            \PHPUnit_Framework_Error::class,
            $this->buildRegex('__construct', CollectionInterface::class)
        );

        /** @noinspection PhpParamsInspection */
        new Runner();
    }

    /**
     * @covers ::getOutput
     */
    final public function testRunnerShouldContainEmptyOutputWhenInstantiated()
    {
        $runner = $this->runner;
        $output = $runner->getOutput();
        $this->assertEquals($output, '');
    }

    /**
     * @covers ::getErrorCode
     */
    final public function testRunnerShouldContainZeroErrorsWhenInstantiated()
    {
        $runner = $this->runner;
        $errorCode = $runner->getErrorCode();
        $this->assertEquals($errorCode, 0);
    }

    /**
     * @covers ::getErrorCode
     */
    final public function testRunnerShouldFetchErrorCodesFromConnectorsWhenAskedForErrorCode()
    {
        $runner = $this->runner;

        $mockConnector = $this->getMockConnector();

        $mockConnector->expects($this->exactly(3))
            ->method('getErrorCode')
            ->willReturnOnConsecutiveCalls(0, 1, 2)
        ;

        $this->iterator->append($mockConnector);
        $this->iterator->append($mockConnector);
        $this->iterator->append($mockConnector);

        $errorCode = $runner->getErrorCode();

        return $errorCode;
    }

    /**
     * @covers ::getOutput
     */
    final public function testRunnerShouldFetchOutputFromConnectorsWhenAskedForOutput()
    {
        $runner = $this->runner;

        $mockConnector = $this->getMockConnector();

        $mockConnector->expects($this->exactly(3))
            ->method('getOutput')
            ->willReturnOnConsecutiveCalls('foo', ' ', 'bar')
        ;

        $this->iterator->append($mockConnector);
        $this->iterator->append($mockConnector);
        $this->iterator->append($mockConnector);

        $output = $runner->getOutput();

        return $output;
    }


    /**
     * @param int $errorCode
     *
     * @depends testRunnerShouldFetchErrorCodesFromConnectorsWhenAskedForErrorCode
     */
    final public function testRunnerShouldPassOnErrorCodeFromConnectorsWhenAskedForErrorCode($errorCode)
    {
        $this->assertEquals($errorCode, 3, 'ErrorCode did not match expected value');
    }

    /**
     * @param string $output
     *
     * @depends testRunnerShouldFetchOutputFromConnectorsWhenAskedForOutput
     */
    final public function testRunnerShouldPassOnOutputFromConnectorsWhenAskedForOutput($output)
    {
        $this->assertEquals($output, 'foo bar', 'Output did not match expected value');
    }

    /**
     * @covers ::run
     */
    final public function testRunnerShouldRunConnectorsWithGivenParametersWhenAskedToRun()
    {
        $runner = $this->runner;

        $mockConnector = $this->getMockConnector();

        $path = '/foo/bar/baz';
        $changeList = ['foo', 'bar', 'baz'];

        $mockConnector->expects($this->exactly(3))
            ->method('run')
            ->with($path, $changeList)
        ;

        $this->iterator->append($mockConnector);
        $this->iterator->append($mockConnector);
        $this->iterator->append($mockConnector);

        $runner->run($path, $changeList);
    }

    ////////////////////////////// MOCKS AND STUBS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockCollection()
    {
        $this->iterator = new \ArrayIterator();

        $mockCollection = $this->getMock(CollectionInterface::class);

        $mockCollection->expects($this->any())
            ->method('getIterator')
            ->willReturn($this->iterator)
        ;

        return $mockCollection;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockConnector()
    {
        return $this->getMockBuilder(ConnectorInterface::class)->getMock();
    }

    /////////////////////////////// DATAPROVIDERS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @param $methodName
     * @param $parameterClassName
     *
     * @return string
     */
    private function buildRegex($methodName, $parameterClassName)
    {
        $className = $this->getClassUnderTest();
        $className = $this->escapeForRegexp($className);
        $parameterClassName = $this->escapeForRegexp($parameterClassName);

        $format = '/Argument [1-3]{1} passed to %s::%s\(\) must implement interface %s, none given/';

        return sprintf($format, $className, $methodName, $parameterClassName);
    }

    /**
     * @param $className
     *
     * @return mixed
     */
    private function escapeForRegexp($className)
    {
        return str_replace('\\', '\\\\', $className);
    }

    /**
     * @return string
     */
    private function getClassUnderTest()
    {
        $className = get_called_class();
        return substr($className, 0, -strlen('Test'));
    }
}

/*EOF*/
