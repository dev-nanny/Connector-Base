<?php

namespace DevNanny\Connector;

use League\Flysystem\FilesystemInterface;

/**
 * @coversDefaultClass DevNanny\Connector\Locator
 * @covers ::__construct
 * @covers ::<!public>
 */
class LocatorTest extends BaseTestCase
{
    ////////////////////////////////// FIXTURES \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var Locator */
    private $locator;
    /** @var FilesystemInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $mockFileSystem;

    protected function setUp()
    {
        $this->mockFileSystem = $this->getMock(FilesystemInterface::class);
        $this->locator = new Locator($this->mockFileSystem);
    }
    /////////////////////////////////// TESTS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @covers ::__construct
     */
    final public function testLocatorShouldBeGivenFileSystemWhenInstantiated()
    {
        $this->setExpectedExceptionRegExp(
            \PHPUnit_Framework_Error::class,
            $this->regexMustImplementInterface('__construct', FilesystemInterface::class)
        );

        /** @noinspection PhpParamsInspection */
        new Locator();
    }

    /**
     * @covers ::locate
     */
    final public function testLocatorShouldAskGivenFileSystemForFileListWhenAskedToLocateConnectors()
    {
        $locator = $this->locator;
        $mockFileSystem = $this->mockFileSystem;

        $mockFileList = array();

        $mockFileSystem->expects($this->exactly(1))
            ->method('listContents')
            ->with('./', true)
            ->willReturn($mockFileList)
        ;


        $locator->locate();
    }

    /**
     * @covers ::locate
     *
     * @dataProvider provideEmptyComposerJson
     *
     * @param $json
     */
    final public function testLocatorShouldFindComposerFilesWhenAskedToLocateConnectors($json)
    {
        $locator = $this->locator;
        $mockFileSystem = $this->mockFileSystem;

        $this->prepareFileSystem($mockFileSystem, $json);

        $locator->locate();
    }

    /**
     * @covers ::locate
     *
     * @dataProvider provideComposerJsonWithNonExistingPluginClass
     *
     * @param $json
     */
    final public function testLocatorShouldComplaintAboutConnectorsThatDoNotExistWhenAskedToLocateConnectors($json)
    {
        $locator = $this->locator;
        $mockFileSystem = $this->mockFileSystem;

        $this->setExpectedExceptionRegExp(
            \UnexpectedValueException::class,
            $this->createRegexFromFormat(Locator::ERROR_CONNECTOR_NOT_FOUND, 'Foo')
        );

        $this->prepareFileSystem($mockFileSystem, $json);

        $locator->locate();
    }

    /**
     * @covers ::locate
     *
     * @dataProvider provideComposerJsonWithExistingPluginClass
     *
     * @param $json
     */
    final public function testLocatorShouldFindConnectorWhenAskedToLocateConnectors($json)
    {
        $locator = $this->locator;
        $mockFileSystem = $this->mockFileSystem;

        $this->prepareFileSystem($mockFileSystem, $json);

        $locator->locate();
    }
    ////////////////////////////// MOCKS AND STUBS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /////////////////////////////// DATAPROVIDERS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @return array
     */
    final public function provideEmptyComposerJson()
    {
        return array(
            array(<<<'JSON'
{}
JSON
            )
        );
    }

    /**
     * @return array
     */
    final public function provideComposerJsonWithoutAnyPluginClass()
    {
        return array(
            array(<<<'JSON'
{
    "type" : "dev-nanny-connector",
    "extra" : {
        "connector-classes" : []
    }
}
JSON
            )
        );
    }

    /**
     * @return array
     */
    final public function provideComposerJsonWithNonExistingPluginClass()
    {
        return array(
            array(<<<'JSON'
{
    "type" : "dev-nanny-connector",
    "extra" : {
        "connector-classes" : [
            "Foo"
        ]
    }
}
JSON
            )
        );
    }

    /**
     * @return array
     */
    final public function provideComposerJsonWithExistingPluginClass()
    {
        return array(
            array(<<<'JSON'
{
    "type" : "dev-nanny-connector",
    "extra" : {
        "connector-classes" : [
            "DevNanny\\Connector\\Collection"
        ]
    }
}
JSON
            )
        );
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject $mockFileSystem
     * @param string $json
     */
    private function prepareFileSystem(\PHPUnit_Framework_MockObject_MockObject $mockFileSystem, $json)
    {
        $fileName = Locator::LOCATE_FILE;
        $path = 'foo/bar/baz/' . $fileName;
        $mockFileList = array(
            array(
                'basename' => $fileName,
                'path' => $path,
            )
        );

        $mockFileSystem->expects($this->exactly(1))
            ->method('listContents')
            ->with('./', true)
            ->willReturn($mockFileList)
        ;

        $mockFileSystem->expects($this->exactly(1))
            ->method('read')
            ->with($path)
            ->willReturn($json)
        ;
    }
}

/*EOF*/
