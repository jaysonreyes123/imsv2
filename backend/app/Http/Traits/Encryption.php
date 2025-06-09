<?php

namespace App\Http\Traits;

trait Encryption
{
    //
    
    protected $encrypted_field = ['caller_firstname','caller_lastname','caller_contact',
    'contact_no','firstname','lastname','mobile','email_address','contact_no_one','contact_no_two'];
    protected $encryption_method = "AES-128-CBC"; // Define the encryption method and key // You can choose AES-128-CBC or other modes too
    protected $secret_key = "incident-management"; // The key to encrypt data
    protected $secret_iv = "ims"; // Initialization vector (IV)

    static function static_encrypt($field,$data){
        self::encrypt($field,$data);
    }
    protected function encrypt($field,$data){
        if(!in_array($field,$this->encrypted_field)){
            return $data;
        }
        // Hash the key to get a 128-bit key
        $key = hash('sha256', $this->secret_key, true);

        // Create the IV for encryption (should be random and fixed size)
        $iv = substr(hash('sha256', $this->secret_iv), 0, 16);

        // Encrypt the data
        $data_to_encrypt = $data;
        $encrypted_data = openssl_encrypt($data_to_encrypt, $this->encryption_method, $key, 0, $iv);
        return $encrypted_data;
    }
    protected function decrypt($model){
        $model_keys = array_keys($model->getOriginal());
        foreach($model_keys as $model_key){
            if(in_array($model_key,$this->encrypted_field)){
                // Hash the key to get a 256-bit key
                $key = hash('sha256', $this->secret_key, true);
                // Create the IV for encryption (should be random and fixed size)
                $iv = substr(hash('sha256', $this->secret_iv), 0, 16);
                $data_to_decrypt = $model->$model_key;
                $decrypted_data = openssl_decrypt($data_to_decrypt, $this->encryption_method, $key, 0, $iv);
                $model->$model_key = $decrypted_data;
            }
        }
        return $model;
    }
    protected function decrypt_single($field,$data){
        if(!in_array($field,$this->encrypted_field)){
            return $data;
        }
        // Hash the key to get a 256-bit key
        $key = hash('sha256', $this->secret_key, true);

        // Create the IV for encryption (should be random and fixed size)
        $iv = substr(hash('sha256', $this->secret_iv), 0, 16);

        // Encrypt the data
        $data_to_encrypt = $data;
        $decrypted_data = openssl_decrypt($data_to_encrypt, $this->encryption_method, $key, 0, $iv);
        return $decrypted_data == false ? "" : $decrypted_data ;
    }
}
