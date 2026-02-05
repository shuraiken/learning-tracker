<?php

namespace App\Exceptions;

use Exception;

class CurrentSessionNotFoundException extends Exception
{
    public function __construct($message = "Current Session Not Found")
    {
        parent::__construct($message);
    }
}
