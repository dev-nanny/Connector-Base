<?php

namespace DevNanny\Connector;

use DevNanny\Connector\Interfaces\LocatorInterface;
use League\Flysystem\FilesystemInterface;

/**
 *
 */
class Locator implements LocatorInterface
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    const COMPOSER_TYPE = 'dev-nanny-connector';

    const ERROR_CONNECTOR_NOT_FOUND = 'The following Connector Class(es) could not be found: "%s"';

    const LOCATE_FILE = 'composer.json';

    /** @var bool */
    private $strict = true;
    /** @var FilesystemInterface */
    private $fileSystem;

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @param FilesystemInterface $fileSystem
     */
    final public function __construct(FilesystemInterface $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * @return array
     */
    final public function locate()
    {
        $files = $this->getComposerFiles();

        $connectors = $this->getConnectorsFromComposerFiles($files);

        return $connectors;
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @param string $json
     *
     * @return string[]
     */
    private function getConnectorsFromExtraSection($json)
    {
        $connectors = [];

        $notFound = [];
        foreach ($json['extra']['connector-classes'] as $connector) {
            if ($this->strict === false || class_exists($connector)) {
                $connectors[] = $connector;
            } else {
                $notFound[] = $connector;
            }
        }

        if (empty($notFound) === false) {
            $message = sprintf(
                self::ERROR_CONNECTOR_NOT_FOUND,
                implode('", "', $notFound)
            );
            throw new \UnexpectedValueException($message);
        } else {
            return $connectors;
        }
    }

    /**
     * @return array
     */
    private function getComposerFiles()
    {
        $files = [];

        $fileList = $this->fileSystem->listContents('./', true);

        foreach ($fileList as $file) {
            if ($file['basename'] === self::LOCATE_FILE) {
                $files[] = $file['path'];
            }
        }

        return $files;
    }

    /**
     * @param $files
     *
     * @return array
     */
    private function getConnectorsFromComposerFiles($files)
    {
        $connectors = [];

        foreach ($files as $file) {
            $json = $this->getJsonContent($file);
            if (isset($json['type']) && $json['type'] === self::COMPOSER_TYPE) {
                //@TODO: Section Validation belongs in the ComposerConnectorInstaller package
                // $this->validateExtraSection($json, basename($file));
                $connectors = array_merge(
                    $connectors,
                    $this->getConnectorsFromExtraSection($json)
                );
            }
        }

        return $connectors;
    }

    /**
     * @param $file
     *
     * @return mixed
     */
    private function getJsonContent($file)
    {
        $json = $this->fileSystem->read($file);

        $data = json_decode($json, true);

        return $data;
    }
}

/*EOF*/
