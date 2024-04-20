<?php

namespace Secretwebmaster\LaravelNamesilo\Operations;

class Account extends AbstractOperation
{
    /**
     * ----------------------------------------------------------------------------------------------------
     * Example
     * ----------------------------------------------------------------------------------------------------
     * @link 
     * @since 1.0.0
     * @version 1.0.0
     * ----------------------------------------------------------------------------------------------------
     */
    public function example()
    {
        $options = 'operationName';
        $params = [];

        return $this->client->fetch($options, $params);
    }
}
