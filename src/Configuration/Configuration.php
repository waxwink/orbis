<?php

namespace Waxwink\Orbis\Configuration;

class Configuration implements ConfigurationInterface
{
    protected string $env = "dev";
    protected bool $debug = true;
    protected string $basePath = __DIR__;
    protected string $configPath = __DIR__ . "/config";

    protected array $config = [];

    public function __construct($config)
    {
        $this->basePath = $config['basePath'] ?? pathinfo($_SERVER["SCRIPT_FILENAME"], PATHINFO_DIRNAME);
        $this->configPath = removeDuplicateSlashes($config['configPath'] ?? $this->basePath . '/config');
        isset($config['env']) && $this->env = $config['env'];
        isset($config['debug']) && $this->debug = $config['debug'];

        $this->config = $config;
        $this->config['app']['basePath'] = $this->basePath;
        $this->config['app']['configPath'] = $this->configPath;
        $this->config['app']['env'] = $this->env;
        $this->config['app']['debug'] = $this->debug;
    }

    /**
     * @return string
     */
    public function getEnv(): string
    {
        return $this->env;
    }

    public function get(string $string)
    {
        $array = explode(".", $string);
        return $this->searchInArray($array, $this->config) ??
            $this->searchInConfigFile($array) ??
            $this->searchInArray(array_slice($array, 1), $this->config);
    }

    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @return array|mixed|string|string[]
     */
    public function getBasePath(): mixed
    {
        return $this->basePath;
    }

    /**
     * @return string
     */
    public function getConfigPath(): string
    {
        return $this->configPath;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }



    private function searchInConfigFile(array $array)
    {
        $filePath = removeDuplicateSlashes($this->configPath . '/' . array_shift($array) . '.php');
        if (! file_exists($filePath)) {
            return null;
        }

        $configInFile = require $filePath;
        if (!is_array($configInFile)) {
            throw new ConfigurationException("Configuration defined in the file ". $configInFile . " is not array");
        }

        return $this->searchInArray($array, $configInFile);
    }

    private function searchInArray(array $array, array $config)
    {
        foreach ($array as $item) {
            if (! array_key_exists($item, $config)) {
                return null;
            }
            $config = $config[$item];
        }

        return $config;
    }
}
