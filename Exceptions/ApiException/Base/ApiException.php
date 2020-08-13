<?php

namespace App\Exceptions\ApiException\Base;
use App\Exceptions\Base;

class ApiException extends Base\ModalTemanException
{
    public function __construct( array $args ) {
        parent::buildResponse( $args );
    }
}
