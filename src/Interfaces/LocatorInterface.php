<?php

namespace DevNanny\Connector\Interfaces;

use League\Flysystem\FilesystemInterface;

/**
 * Interface for Connector Locator Classes
 */
interface LocatorInterface
{
    /**
     * The filesystem to locate file(s) in
     *
     * @param FilesystemInterface $fileSystem
     */
    public function __construct(FilesystemInterface $fileSystem);

    /**
     * Locate Connector classes in the given filesystem, returns an array with
     * fully qualified class names of found Connectors.
     *
     * @return string[]
     */
    public function locate();
}

/*EOF*/
