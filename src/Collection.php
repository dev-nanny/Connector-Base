<?php

namespace DevNanny\Connector;

use DevNanny\Connector\Interfaces\CollectionInterface;
use DevNanny\Connector\Interfaces\ConnectorInterface;

/**
 * Class to hold a Collection of Connectors
 */
class Collection implements CollectionInterface
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var ConnectorInterface[]|\ArrayIterator */
    private $connectors;

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * Retrieve internal iterator
     *
     * @see http://php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @return array
     */
    final public function getIterator()
    {
        return $this->connectors;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     *
     */
    final public function __construct()
    {
        $this->connectors = new \ArrayIterator;
    }

    /**
     * @param ConnectorInterface $connector
     */
    final public function add(ConnectorInterface $connector)
    {
        $this->connectors[] = $connector;
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
}

/*EOF*/
