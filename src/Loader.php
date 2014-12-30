<?php

namespace DevNanny\Connector;

use DevNanny\Connector\Interfaces\CollectionInterface;
use DevNanny\Connector\Interfaces\ConnectorInterface;
use DevNanny\Connector\Interfaces\LocatorInterface;
//@TODO: Wrap Flysystem in a Proxy Class
use League\Flysystem\FilesystemInterface;

/**
 * Class to Loader Connectors
 */
class Loader
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var CollectionInterface */
    private $collection;
    /** @var FilesystemInterface */
    private $locator;

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @param CollectionInterface $collection
     * @param LocatorInterface $locator
     */
    final  public function __construct(CollectionInterface $collection, LocatorInterface $locator)
    {
        $this->collection = $collection;
        $this->locator = $locator;
    }

    final public function loadConnectors()
    {
        $connectors = $this->getConnectorsFromFileList();
        $this->addConnectorsToCollection($connectors);
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @return array
     */
    private function getConnectorsFromFileList()
    {
        $connectors = [];

        $connectorClasses = $this->locator->locate();

        foreach ($connectorClasses as $connectorClass) {
            // @FIXME: Use Dependency Injection Container for connectors that have dependencies
            $connectors[] = new $connectorClass;
        }


        return $connectors;
    }

    /**
     * @param ConnectorInterface[] $connectors
     */
    private function addConnectorsToCollection(array $connectors)
    {
        foreach ($connectors as $connector) {
            $this->collection->add($connector);
        }
    }
}

/*EOF*/
