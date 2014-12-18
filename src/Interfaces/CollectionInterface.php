<?php

namespace DevNanny\Connector\Interfaces;

/**
 * Interface for Collection classes
 */
interface CollectionInterface extends \IteratorAggregate
{
    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @param ConnectorInterface $connector
     */
    public function add(ConnectorInterface $connector);
}

/*EOF*/
