<?php

namespace BVN\Config;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

class YamlConfigLoader extends FileLoader
{
    /**
     * @param mixed $name
     * @param null $type
     * @return mixed
     */
    public function load($name, $type = null)
    {
        $resource = $this->getLocator()->locate($name);
        $configValues = Yaml::parse(file_get_contents($resource));

        return $configValues;
    }

    /**
     * @param mixed $resource
     * @param null $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo(
                $resource,
                PATHINFO_EXTENSION
            );
    }
}