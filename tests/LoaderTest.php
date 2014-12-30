<?php

namespace DevNanny\Connector;

use DevNanny\Connector\Interfaces\CollectionInterface;
use DevNanny\Connector\Interfaces\ConnectorInterface;
use DevNanny\Connector\Interfaces\LocatorInterface;

/**
 * @coversDefaultClass DevNanny\Connector\Loader
 * @covers ::<!public>
 * @covers ::__construct
 *
 * @uses \Doctrine\Instantiator\Instantiator
 */
class LoaderTest extends BaseTestCase
{
    ////////////////////////////////// FIXTURES \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var Loader */
    private $loader;
    /** @var CollectionInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $mockCollection;
    /** @var LocatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $mockLocator;

    protected function setUp()
    {
        $this->mockCollection = $this->getMock(CollectionInterface::class);
        $this->mockLocator = $this->getMock(LocatorInterface::class);

        $this->loader = new Loader($this->mockCollection, $this->mockLocator);
    }
    /////////////////////////////////// TESTS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     *
     */
    final public function testLoaderShouldBeGivenCollectionWhenInstantiated()
    {
        $this->setExpectedExceptionRegExp(
            \PHPUnit_Framework_Error::class,
            $this->regexMustImplementInterface('__construct', CollectionInterface::class)
        );

        /** @noinspection PhpParamsInspection */
        new Loader();
    }

    /**
     *
     */
    final public function testLoaderShouldBeGivenLocatorWhenInstantiated()
    {
        $this->setExpectedExceptionRegExp(
            \PHPUnit_Framework_Error::class,
            $this->regexMustImplementInterface('__construct', LocatorInterface::class)
        );

        /** @noinspection PhpParamsInspection */
        new Loader($this->mockCollection);
    }

    /**
     * @covers ::loadConnectors
     */
    final public function testLoaderShouldGetConnectorsFromLocatorWhenAskedToLoadConnectors()
    {
        $loader = $this->loader;

        $mockLocator = $this->mockLocator;
        $mockFileList = [];

        $mockLocator->expects($this->exactly(1))
            ->method('locate')
            ->willReturn($mockFileList)
        ;

        $loader->loadConnectors();
    }

    /**
     * @covers ::loadConnectors
     */
    final public function testLoaderShouldAddConnectorsFromLocatorToCollectionWhenAskedToLoadConnectors()
    {
        $loader = $this->loader;

        $mockLocator = $this->mockLocator;
        $mockConnector = $this->getMock(ConnectorInterface::class);

        $className = get_class($mockConnector);
        $mockFileList = array($className);
        $count = count($mockFileList);

        $mockLocator->expects($this->exactly(1))
            ->method('locate')
            ->willReturn($mockFileList)
        ;

        $this->mockCollection->expects($this->exactly($count))
            ->method('add')
            ->with($mockConnector)
        ;

        $loader->loadConnectors();
    }
    ////////////////////////////// MOCKS AND STUBS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /////////////////////////////// DATAPROVIDERS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
}

/*EOF*/
