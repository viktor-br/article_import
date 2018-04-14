<?php

namespace BVN\Command;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;

abstract class AbstractCommand extends Command
{
    /** @var ContainerInterface */
    protected $container;

    public function __construct(?string $name = null, ContainerInterface $container)
    {
        parent::__construct($name);

        $this->container = $container;
    }
}
