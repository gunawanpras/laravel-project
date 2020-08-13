<?php

namespace App\Exceptions\ApiException;

class JWTException extends Base\ApiException
{
    public const HTTP_STATUS = 401;
    public const HTTP_STATUS_TEXT = 'Unauthorized';
    
    public function __construct()
    {
        parent::__construct( func_get_args() );
    }
}
