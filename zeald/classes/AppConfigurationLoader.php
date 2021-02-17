<?php

namespace Zeald\nba2019\classes;

/**
 * Class ConfigLoader
 * @package Zeald\nba2019\classes
 * @author Noel Palo
 * @license
 */
class AppConfigurationLoader
{
    /**
     * @var string
     */
    private $configFile;

    /**
     * AppConfigurationLoader constructor.
     * @param string $configFile
     */
    public function __construct(string $configFile)
    {
        $pathPrefix = "./../..";
        $configDir = "config";
        $configExt = "json";
        $this->configFile = "$pathPrefix/$configDir/$configFile.$configExt";
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return json_decode(
            file_get_contents(__DIR__ . $this->configFile),
            true
        );
    }
}