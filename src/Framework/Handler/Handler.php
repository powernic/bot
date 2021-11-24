<?php

namespace Powernic\Bot\Framework\Handler;

use Powernic\Bot\Framework\Form\Form;
use Psr\Container\ContainerInterface;

abstract class Handler implements HandlerInterface
{
    protected ContainerInterface $container;

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    protected function createForm(string $type, $data = null): Form
    {
        return $this->container->get('form.factory')->create($type, $data);
    }
}
