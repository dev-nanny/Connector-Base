<?php

namespace DevNanny\Connector;

use DevNanny\Connector\Interfaces\ConnectorInterface;

/**
 * @coversDefaultClass DevNanny\Connector\Collection
 * @covers ::<!public>
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{
    ////////////////////////////////// FIXTURES \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var Collection */
    private $collection;

    protected function setUp()
    {
        $this->collection = new Collection();
    }

    /////////////////////////////////// TESTS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @covers ::getIterator
     */
    final public function testCollectionShouldContainZeroConnectorsWhenInstantiated()
    {
        $collection = $this->collection;

        $actual = $collection->getIterator();

        $this->assertEmpty($actual);
    }

    /**
     * @covers ::add
     * @covers ::getIterator
     */
    final public function testCollectionShouldAddConnectorWhenGivenConnector()
    {
        $collection = $this->collection;
        $expected = $this->getMockConnector();

        $collection->add($expected);
        $traversable = $collection->getIterator();
        $actual = current($traversable);

        $this->assertEquals($expected, $actual);
    }

    ////////////////////////////// MOCKS AND STUBS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @return ConnectorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockConnector()
    {
        return $this->getMockBuilder(ConnectorInterface::class)->getMock();
    }

    /////////////////////////////// DATAPROVIDERS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
}

/*EOF*/
