<?php

namespace App\Exceptions\Base;
use App\Exceptions\ExceptionConst as CONSTANT;

/**
 * Abstract class: ModalTemanException
 * 
 */

abstract class ModalTemanException extends \Exception implements ModalTemanThrowable
{
    /**
     * @var code
     */
    protected $code;
    
    /**
     * @var status
     */
    protected $status;
    
    /**
     * @var title
     */
    protected $title;
    
    /**
     * @var description
     */
    protected $description;

    public function __construct() {}

    private function processArgs(array $args)
    {
        $info = CONSTANT::GENERAL_ERROR_DESC;        

        $error = array_shift( $args );
        $className = \get_called_class();

        if (is_string( $error )) {
            if (! preg_match('/^[A-Z]+_/', $error)) {
                $info['description'] = $error;
            } else {
                if ( \defined( "App\Exceptions\ExceptionConst::$error" ) ) {
                    $info = constant( "App\Exceptions\ExceptionConst::$error" );
                }
            }

        } else if (\is_array( $error )) {
            $info['description'] = $error;
        }

        $info['status'] = $className::HTTP_STATUS;
        $info['title'] = $className::HTTP_STATUS_TEXT;

        return $info;
    }

    public function buildResponse( array $args )
    {
        $infos = $this->processArgs( $args );

        $this->status = $infos['status'];
        $this->code = $infos['code'];
        $this->title = $infos['title'];
        $this->description = $infos['description'];
    }

    public function render()
    {
        return response (
        [
            'code' => $this->code,
            'error' => $this->title,
            'description' => $this->description,
        ], 
        
        $this->status)

        ->withHeaders ([
            'content-type'=>'application/json'
        ]);
    }
}
