<?php

namespace DevNanny\Connector;

use DevNanny\Connector\Interfaces\CollectionInterface;
use DevNanny\Connector\Interfaces\ConnectorInterface;
use League\Flysystem\FilesystemInterface;

/**
 * @coversDefaultClass DevNanny\Connector\Runner
 * @covers ::<!public>
 * @covers ::__construct
 */
class RunnerTest extends BaseTestCase
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
            $this->regexMustImplementInterface('__construct', CollectionInterface::class)
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

        /** @var FilesystemInterface|\PHPUnit_Framework_MockObject_MockObject $mockFileSystem */
        $mockFileSystem = $this->getMock(FilesystemInterface::class);
        $changeList = ['foo', 'bar', 'baz'];

        $mockConnector->expects($this->exactly(3))
            ->method('run')
            ->with($mockFileSystem, $changeList)
        ;

        $this->iterator->append($mockConnector);
        $this->iterator->append($mockConnector);
        $this->iterator->append($mockConnector);

        $runner->run($mockFileSystem, $changeList);
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
}

/*EOF*/
