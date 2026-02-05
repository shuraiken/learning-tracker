<?php

namespace App\Exceptions;

use Exception;

class ActiveSessionAlreadyExistsException extends Exception
{
    public function __construct($message = "Active Session Already Exists")
    {
        parent::__construct($message, 409);
    }
}
