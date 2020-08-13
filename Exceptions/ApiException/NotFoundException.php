<?php
namespace App\Exceptions\ApiException;

class NotFoundException extends Base\ApiException
{
    public const HTTP_STATUS = 404;
    public const HTTP_STATUS_TEXT = 'Not Found';

    public function __construct()
    {
        parent::__construct( func_get_args() );
    }
}
