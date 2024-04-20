<?php

namespace Secretwebmaster\LaravelNamesilo\Operations;

Use Secretwebmaster\LaravelNamesilo\Namesilo;

abstract class AbstractModel
{
    protected $client;
    protected $operation;
    
    public function __construct(Namesilo $client, $data)
    {
        $this->client = $client;

    }
}