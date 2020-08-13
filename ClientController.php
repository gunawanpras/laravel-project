<?php
namespace App\Http\Controllers;

use App\Model\User;
use App\Exceptions\ApiException\NotFoundException;
use App\Exceptions\ApiException\ValidationException;
use Carbon\Carbon;
use File;
use CONSTANT;

class ClientController extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    public function show($user_id) {
        try {
            $user = User::find( $user_id );

            if ( ! $user ) {
                throw new NotFoundException( 'USER_NOT_FOUND' );
            }

            if ( $this->request->header('platform') === 'client.p2p.co.id' ) {
                $profile = self::USER_MODEL_CLIENT[ $user->user_type ];
            } else if ( $this->request->header('platform') === 'partner.p2p.co.id' ) {
                $profile = self::USER_MODEL_PARTNER[ $user->user_type ];
            }

            $user->{$profile};

            $data = [
                'user' => $user->{$profile}
            ];

            return self::__success(CONSTANT::DEFAULT, CONSTANT::SUCCESS, $data);

        } catch (NotFoundException $e) {
            throw $e;
        }
    }

    public function update($user_id) {
        try {
            $user = User::find( $user_id );
    
            if ( ! $user ) {
                throw new NotFoundException( 'USER_NOT_FOUND' );
            }
    
            $data = array_filter ( $this->request->all() );

            if ( $user->user_type == 1 ) {
                $user->clientPersonal()->update($data);
            }
            
            else if ( $user->user_type == 2 ) {
                $user->clientCorporate()->update($data);
            }
    
            return self::__success(CONSTANT::DEFAULT, CONSTANT::SUCCESS, $user->user_id);
        } catch(ValidationException $e) {
            throw $e;
        } catch(NotFoundException $e) {
            throw $e;
        }
    }

    public function getUserParams( User $user ) {
        if ( $user->user_type == 1 ) {
            return [
                'user_id' => $user->user_id, 
                'phone_no' => $this->request->input('phone_no'),
                'gender' => $this->request->input('gender'),
                'ktp_no' => $this->request->input('ktp_no'),
                'date_birth' => $this->request->input('date_birth'),
                'place_birth' => $this->request->input('place_birth'),
                'address_card' => $this->request->input('address_card'),                
                'province' => $this->request->input('province'),
                'city' => $this->request->input('city'),
                'kecamatan' => $this->request->input('kecamatan'),
                'kelurahan' => $this->request->input('kelurahan'),
                'address_residence' => $this->request->input('address_residence'),                
                'province_residence' => $this->request->input('province_residence'),
                'city_residence' => $this->request->input('city_residence'),
                'postal_code_residence' => $this->request->input('postal_code_residence'),
                'npwp_no' => $this->request->input('npwp_no'),
                'occupation' => $this->request->input('occupation'),
                'bank_name' => $this->request->input('bank_name'),
                'bank_account_no' => $this->request->input('bank_account_no'),
                'bank_account_holder' => $this->request->input('bank_account_holder'),
                'avatars' => $this->request->input('avatars'),
                'ktp_upload' => $this->request->input('ktp_upload'),
                'npwp_upload' => $this->request->input('npwp_upload'),
            ];

        } else if ( $user->user_type ==2 ) {
            return [
                'user_id' => $user->user_id, 
                'phone_no' => $this->request->input('phone_no'), 
                'avatars' => $this->request->file('avatars')->store($uploadDirPath . '/avatars'), 
                'gender' => $this->request->input('gender'), 
                'ktp_no'=> $this->request->input('ktp_no'), 
                'ktp_upload' => $this->request->file('ktp_upload')->store($uploadDirPath . '/ktp'), 
                'date_birth'=> $this->request->input('date_birth'), 
                'place_birth'=> $this->request->input('place_birth'), 
                'address_card'=> $this->request->input('address_card'), 
                'city'=> $this->request->input('city'), 
                'province'=> $this->request->input('province'), 
                'postal_code'=> $this->request->input('postal_code'), 
                'address_residence'=> $this->request->input('address_residence'), 
                'city_residence' => $this->request->input('city_residence'), 
                'province_residence'=> $this->request->input('province_residence'), 
                'postal_code_residence'=> $this->request->input('postal_code_residence')
            ];
        }

        return [];
    }
}