<?php

namespace Module\Product\Exceptions;

class ProductNotFoundException extends \Exception
{
    public function __construct(string $message = null,)
    {

        parent::__construct($message ?? 'Product not found');
    }
}
