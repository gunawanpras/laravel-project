<?php

namespace App\Exceptions\ApiException;

class ValidationException extends Base\ApiException
{
    public const HTTP_STATUS = 400;
    public const HTTP_STATUS_TEXT = 'Bad Request';
    
    public function __construct()
    {
        parent::__construct( func_get_args() );
    }
}
