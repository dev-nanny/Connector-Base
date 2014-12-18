<?php

namespace DevNanny\Connector\Interfaces;

/**
 * An implementing class should act as a bridge between any tool or command that
 * is to be run against the code-base as a git-hook, and the actual git-hook.
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
     * @param string $path The path to run the connected tool against
     * @param array $changeList Optional, the files that have changed
     *
     * @return null
     */
    public function run($path, array $changeList = []);
}

/*EOF*/
