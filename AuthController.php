<?php

namespace App\Http\Controllers;

use App\Model\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\ApiException\UnauthorizedException;
use App\Exceptions\ApiException\ValidationException;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

use Illuminate\Support\Facades\Cookie;

use CONSTANT;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function token()
    {
        $payload = [
            'iss' => 'api.p2p.co.id',
            'sub' => '0',
            'iat' => time(),
            'exp' => time() + ((3600 * 24 * 30) * 12),
            'info' => [
                'platform' => 'web',
                'role' => 'Guest',
                'role_id' => 0
            ]
        ];

        echo self::__success(CONSTANT::DEFAULT, CONSTANT::SUCCESS, ['token' => $this->jwt( $payload )]);
    }    

    public function authenticate(User $user)
    {
        try {
            $this->validator->make([
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            $user = User::where('email', $this->request->input('email'))->first();            
    
            if ( ! $user ) {
                throw new UnauthorizedException( 'EMAIL_INVALID' );
            }
    
            if ( ! Hash::check( $this->request->input('password'), $user->password ) ) {
                throw new UnauthorizedException( 'EMAIL_INVALID' );
            }

            if ( $this->request->header('platform') === 'client.p2p.co.id' ) {
                $profile = self::USER_MODEL_CLIENT[ $user->user_type ];
            } else if ( $this->request->header('platform') === 'partner.p2p.co.id' ) {
                $profile = self::USER_MODEL_PARTNER[ $user->user_type ];
            }
            
            $user->{$profile};

            $payload = [
                'iss' => 'api.p2p.co.id',
                'sub' => $user->user_id,
                'iat' => time(),
                'exp' => time() + (60 * 60) * 2,
                'info' => [
                    'platform' => $this->request->header('platform'),
                    'role' => 'User',
                    'role_id' => 0
                ]
            ];

            $token = $this->jwt( $payload );

            $redirectUrl = "/profile" . self::USER_URL_PATH[$user->user_type] ."/{$user->user_id}";
            if (empty ($user->{$model}->ktp_no) ) {
                $redirectUrl = "/profile". self::USER_URL_PATH[$user->user_type] ."/{$user->user_id}/wizard?phone_no={$user->{$model}->phone_no}";
            }

            $data = [
                'user_id' => $user->user_id,
                'user_type' => $user->user_type,
                'token' => $token,
                'redirectUrl' => $redirectUrl,
            ];

            return self::__success(CONSTANT::DEFAULT, CONSTANT::SUCCESS, $data);
            
        } catch (UnauthorizedException $e) {
            throw $e;
        }
    }

    public function register() {
        try {
            $this->validator->make([
                'user_type' => 'required',
                'email' => 'required|email|unique:users',
                'phone_no' => 'required',
                'terms_n_cond_1' => 'required',
                'terms_n_cond_2' => 'required',
            ]);

            $user = new User;
            $user->user_type = $this->request->input('user_type');
            $user->email = $this->request->input('email');
            $user->password = Hash::make($this->request->input('password'));
            $user->save();

            $userId = $user->user_id;
            
            if ( $this->request->header('platform') === 'client.p2p.co.id' ) {
                $model = 'App\\Model\\' . self::USER_MODEL_CLIENT[ $this->request->input('user_type') ];
            } else if ( $this->request->header('platform') === 'partner.p2p.co.id' ) {
                $model = 'App\\Model\\' . self::USER_MODEL_PARTNER[ $this->request->input('user_type') ];
            }
            
            $model::create([
                'user_id' => $userId, 
                'phone_no' => $this->request->input('phone_no') 
            ]);

            $data = [ 'user_id' => $userId ];

            $this->emailVerify->send([
                'action'   => 'registration', 
                'user'      =>  $user,
                'endpoint'  => '/auth/account-verify',
                'view'      => 'emails.tpl',
            ]);

            return self::__success(CONSTANT::DEFAULT, CONSTANT::SUCCESS, $data);

        } catch (ValidationException $e) {
            throw $e;
        }
    }    

    public function userVerification() {
        $user = $this->emailVerify->decode();
        $user->email_verified_at = Carbon::now();
        $user->save();

        return self::__success(CONSTANT::DEFAULT, CONSTANT::SUCCESS, ['user_id' => $user->user_id]);
    }    

    public function resetPasswordStepOne() {
        try {
            $this->validator->make([
                'email' => 'required'
            ]);

            $user = User::where('email', $this->request->input('email'))->first();
        
            if ( ! $user ) {
                throw new UnauthorizedException( 'EMAIL_NOT_FOUND' );
            }

            $this->emailVerify->send([
                'action'   => 'reset-password', 
                'user'      =>  $user,
                'endpoint'  => '/user/reset-password/step-2',
                'view'      => 'emails.tpl',
            ]);

            return self::__success(CONSTANT::DEFAULT, CONSTANT::SUCCESS);
        } catch (UnauthorizedException $e) {
            throw $e;
        } 
    }

    public function resetPasswordStepTwo() {
        $user = $this->emailVerify->decode();
        
        return self::__success(CONSTANT::DEFAULT, CONSTANT::SUCCESS, $user);
    }

    public function resetPasswordStepThree() {
        $this->validator->make([
            'user_id' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required|same:password',
        ]);

        $user = User::find( $this->request->input('user_id') );
        $user->password = Hash::make($this->request->input('password'));
        $user->save();

        return self::__success(CONSTANT::DEFAULT, CONSTANT::SUCCESS, $user);
    }
}
