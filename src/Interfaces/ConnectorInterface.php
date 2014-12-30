<?php

namespace DevNanny\Connector\Interfaces;

use League\Flysystem\FilesystemInterface;

/**
 * An implementing class should act as a bridge between any tool or command that
 * is to be run against the code-base, and the actual code-base.
 *
 * It receives the filesystem and a change-set (in situations this makes sense)
 * and should decide for itself if (and which) actions should be taken on "run".
 */
interface ConnectorInterface
{
    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @return int
     */
    public function getErrorCode();

    /**
     * @return string
     */
    public function getOutput();

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * This method should be implemented by an extending class to run whichever
     * tool it acts as a bridge for, storing the output and error-code of that
     * tool to be retrieved at a later point.
     *
     * @param FilesystemInterface $filesystem The filesystem to run the connected tool against
     * @param array $changeList Optional, the files that have changed
     *
     * @return null
     */
    public function run(FilesystemInterface $filesystem, array $changeList = []);
}

/*EOF*/
