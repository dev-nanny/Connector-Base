<?php

namespace DevNanny\Connector;

use DevNanny\Connector\Interfaces\CollectionInterface;
use DevNanny\Connector\Interfaces\ConnectorInterface;

/**
 * Executes all Connectors for a given Collection, taking note of output and
 * error codes from those connectors.
 */
class Runner implements ConnectorInterface
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var CollectionInterface|ConnectorInterface[] */
    private $connectors;

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @return int
     */
    final public function getErrorCode()
    {
        $errorCode = 0;

        foreach ($this->connectors as $hook) {
            $errorCode += (int) $hook->getErrorCode();
        }

        return $errorCode;
    }

    /**
     * @return string
     */
    final public function getOutput()
    {
        $output = '';

        foreach ($this->connectors as $connector) {
            $output .= $connector->getOutput();
        }

        return $output;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @param CollectionInterface $connectors
     */
    final public function __construct(CollectionInterface $connectors)
    {
        $this->connectors = $connectors;
    }

    /**
     * @param string $path
     * @param array $changeList
     *
     * @return void
     */
    final public function run($path, array $changeList = [])
    {
        foreach ($this->connectors as $connector) {
            $connector->run($path, $changeList);
        }
    }
}

/*EOF*/
