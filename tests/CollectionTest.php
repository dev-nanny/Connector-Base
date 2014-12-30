<?php

namespace DevNanny\Connector;

use DevNanny\Connector\Interfaces\ConnectorInterface;

/**
 * @coversDefaultClass DevNanny\Connector\Collection
 * @covers ::__construct
 * @covers ::<!public>
 */
class CollectionTest extends BaseTestCase
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
     */
    final public function testCollectionShouldRefuseToAddConnectorsWhenAskedToAddIncorrectType()
    {
        $this->setExpectedExceptionRegExp(
            \PHPUnit_Framework_Error::class,
            $this->regexMustBeAnInstanceOf('add', ConnectorInterface::class)
        );
        $collection = $this->collection;

        /** @noinspection PhpParamsInspection */
        $collection->add('foo');
    }

    /**
     * @covers ::add
     * @covers ::getIterator
     */
    final public function testCollectionShouldAddConnectorWhenAskedToAddConnector()
    {
        $collection = $this->collection;
        $expected = $this->getMockConnector();

        $collection->add($expected);
        $traversable = $collection->getIterator();
        $actual = current($traversable);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers ::getIterator
     * @covers ::add
     */
    final public function testCollectionShouldBeIteratableWhenIterated()
    {
        $collection = $this->collection;
        $expected = $this->getMockConnector();

        $collection->add($expected);

        foreach ($collection as $actual) {
            $this->assertSame($expected, $actual);
        }

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
