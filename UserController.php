<?php

namespace App\Http\Controllers;

use Validator;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\ApiException\UnauthorizedException;
use App\Exceptions\ApiException\ValidationException;
use Illuminate\Support\Exceptions\MethodNotAllowedHttpException;
use Illuminate\Support\Facades\Mail;
use App\Mail\AwsSes;

class UserController extends Controller
{
    private $request;

    public function __construct(Request $request) {
        parent::__construct( $request );
    }

    public function changePassword()
    {
        try {
            $this->validator->make([
                'current_password' => 'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required|same:password',
            ]);

            $currentPassword = $this->request->input('current_password');
            $password = $this->request->input('password');

            $user = User::where([
                'user_id' => $this->request->user_id,
                'password' => Hash::make($currentPassword),
            ])->first();

            if ( ! $user ) {
                throw new UnauthorizedException( 'CURRENT_PASSWORD_MISMATCH' );
            }

            $user->password = Hash::make($password);
            $user->save();

            return self::__success(self::DEFAULT, self::SUCCESS, $user->user_id);

        } catch (MethodNotAllowedHttpException $e) {
            throw new App\Exceptions\ApiException\MethodNotAllowedHttpException( $e->getMessage() );
        } catch (UnauthorizedException $e) {
            throw $e;
        }
    }
}
