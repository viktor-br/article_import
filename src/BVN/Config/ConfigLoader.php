<?php

namespace BVN\Config;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

class ConfigLoader
{
    /**
     * @param string[] $directories
     * @param string[] $fileNames
     * @return array
     * @throws \Exception
     */
    public function load(array $directories, array $fileNames)
    {
        $loader = new YamlConfigLoader(
            new FileLocator($directories)
        );
        $filename = current($fileNames);
        $configValues = $loader->load($filename);
        $processor = new Processor();
        $configuration = new Configuration();
        $processedConfiguration = $processor->processConfiguration(
            $configuration,
            [$configValues]
        );

        return $processedConfiguration;
    }
}