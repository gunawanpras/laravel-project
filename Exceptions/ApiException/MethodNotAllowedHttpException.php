<?php

namespace App\Exceptions\ApiException;

class MethodNotAllowedHttpException extends Base\ApiException
{
    public const HTTP_STATUS = 405;
    public const HTTP_STATUS_TEXT = 'Method Not Allowed';
    
    public function __construct()
    {
        parent::__construct( func_get_args() );
    }
}
