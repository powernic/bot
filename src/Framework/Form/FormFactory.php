<?php

namespace Powernic\Bot\Framework\Form;

class FormFactory
{
    private $registry;

    public function __construct(FormRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function create(string $type = Form::class, $data = null )
    {
        return $this->registry->getType($type);
    }
}
