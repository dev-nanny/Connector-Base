<?php

namespace DevNanny\Connector;

use DevNanny\Connector\Interfaces\CollectionInterface;
use DevNanny\Connector\Interfaces\ConnectorInterface;
use League\Flysystem\FilesystemInterface;

/**
 * Executes all Connectors for a given Collection, taking note of output and
 * error codes from those connectors.
 */
class Runner implements ConnectorInterface
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var CollectionInterface|ConnectorInterface[] */
    private $collection;

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @return int
     */
    final public function getErrorCode()
    {
        $errorCode = 0;

        foreach ($this->collection as $connector) {
            $errorCode += (int) $connector->getErrorCode();
        }

        return $errorCode;
    }

    /**
     * @return string
     */
    final public function getOutput()
    {
        $output = '';

        foreach ($this->collection as $connector) {
            $output .= $connector->getOutput();
        }

        return $output;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @param CollectionInterface $collection
     */
    final public function __construct(CollectionInterface $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @param FilesystemInterface $filesystem
     * @param array $changeList
     *
     * @return void
     */
    final public function run(FilesystemInterface $filesystem, array $changeList = [])
    {
        foreach ($this->collection as $connector) {
            $connector->run($filesystem, $changeList);
        }
    }
}

/*EOF*/
