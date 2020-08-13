<?php

namespace App\Exceptions\ApiException;

class ForbiddenException extends Base\ApiException
{
    public const HTTP_STATUS = 403;
    public const HTTP_STATUS_TEXT = 'Forbidden';
    
    public function __construct()
    {
        parent::__construct( func_get_args() );
    }
}
