<?php

namespace App\Exceptions;

class ExceptionConst
{
    /**
     * HTTP Status List
     * 
     */

    public const DEFAULT = 200;
    public const CREATED = 201;
    public const ACCEPTED = 202;
    public const BAD_REQUEST = 400;
    public const UNAUTHORIZED = 401;
    public const FORBIDDEN = 403;
    public const NOTFOUND = 404;
    public const METHOD_NOT_ALLOWED = 405;
    public const GENERAL_ERROR = 500;

    public const DEFAULT_TEXT = 'Success';
    public const CREATED_TEXT = 'Created';
    public const ACCEPTED_TEXT = 'Accepted';
    public const BAD_REQUEST_TEXT = 'Bad Request';    
    public const UNAUTHORIZED_TEXT = 'Unauthorized';
    public const FORBIDDEN_TEXT = 'Forbidden';
    public const NOTFOUND_TEXT = 'Not Found';
    public const METHOD_NOT_ALLOWED_TEXT = 'Method Not Allowed';
    public const GENERAL_ERROR_TEXT = 'General Error';    

    /**
     * Custom/extra information
     * for your HTTP response
     * 
     */

    public const SUCCESS = ['code'=> 1000, 'title' => 'Success', 'description'=>'Success']; 

    public const TOKEN_INVALID = ['code'=> 1200, 'description'=>'Invalid token'];
    public const TOKEN_REQUIRED = ['code'=> 1201, 'description'=>'Token is missing'];
    public const TOKEN_EXPIRED = ['code'=> 1202, 'description'=>'Token has expired'];
    public const EMAIL_NOT_FOUND = ['code'=> 1203, 'description'=>'Email not found'];
    public const USER_NOT_FOUND = ['code'=> 1205, 'description'=>'User not found'];
    public const EMAIL_INVALID = ['code'=> 1206, 'description'=>'Incorrect username or password. Please try again.'];
    public const CURRENT_PASSWORD_MISMATCH = ['code'=> 1207, 'description'=>'Current password mismatch'];
    public const GENERAL_ERROR_DESC = ['code'=> 3000, 'description'=>"Error has occured"];
}
