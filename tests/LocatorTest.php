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
     */
    final public function testLocatorShouldFindComposerFilesWhenAskedToLocateConnectors()
    {
        $locator = $this->locator;
        $mockFileSystem = $this->mockFileSystem;

        $mockFileList = array(
            array(
                'basename' => 'composer.json',
                'path' => 'tests/fixtures/empty-composer.json',
            )
        );

        $mockFileSystem->expects($this->exactly(1))
            ->method('listContents')
            ->with('./', true)
            ->willReturn($mockFileList)
        ;


        $locator->locate();
    }

    /**
     * @covers ::locate
     */
    final public function testLocatorShouldComplaintAboutConnectorsThatDoNotExistWhenAskedToLocateConnectors()
    {
        $locator = $this->locator;
        $mockFileSystem = $this->mockFileSystem;

        $this->setExpectedExceptionRegExp(
            \UnexpectedValueException::class,
            $this->createRegexFromFormat(Locator::ERROR_CONNECTOR_NOT_FOUND, 'Foo')
        );

        $mockFileList = array(
            array(
                'basename' => 'composer.json',
                'path' => 'tests/fixtures/plugin-composer-with-non-existing-plugin-class.json',
            )
        );

        $mockFileSystem->expects($this->exactly(1))
            ->method('listContents')
            ->with('./', true)
            ->willReturn($mockFileList)
        ;


        $locator->locate();
    }

    /**
     * @covers ::locate
     */
    final public function testLocatorShouldFindConnectorWhenAskedToLocateConnectors()
    {
        $locator = $this->locator;
        $mockFileSystem = $this->mockFileSystem;

        $mockFileList = array(
            array(
                'basename' => 'composer.json',
                'path' => 'tests/fixtures/plugin-composer-with-existing-plugin-class.json',
            )
        );

        $mockFileSystem->expects($this->exactly(1))
            ->method('listContents')
            ->with('./', true)
            ->willReturn($mockFileList)
        ;


        $locator->locate();
    }
    ////////////////////////////// MOCKS AND STUBS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /////////////////////////////// DATAPROVIDERS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
}

/*EOF*/
