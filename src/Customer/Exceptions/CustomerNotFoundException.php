<?php

namespace Module\Customer\Exceptions;

class CustomerNotFoundException extends \Exception
{
    public function __construct(string $message = null)
    {
        parent::__construct($message ?? 'Customer not found');
    }
}
