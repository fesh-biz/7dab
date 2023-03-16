<?php


use Hashids\Hashids;

if (!function_exists('encodeId')) {
    function encodeId($id): string
    {
        $hash = new Hashids(config('7dab.salt'), 10);
        
        return $hash->encode($id);
    }
}

if (!function_exists('encryptor')) {
    function encryptor($action, $string) {
        $output = false;
        
        $encrypt_method = "AES-256-CBC";
        //pls set your unique hashing key
        $secret_key = 'fsdaIOsd3';
        $secret_iv = 'DFewpoDS';
        
        // hash
        $key = hash('sha256', $secret_key);
        
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        
        //do the encyption given text/string/number
        if( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        }
        else if( $action == 'decrypt' ){
            //decrypt the given text/string/number
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        
        return $output;
    }
}

if (!function_exists('decodeId')) {
    function decodeId($id): int
    {
        $hash = new Hashids(config('7dab.salt'), 10);

        return $hash->decode($id)[0];
    }
}


/**
 * dd() with headers
 */
if (!function_exists('ddh')) {
    function ddh($var){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: *');
        dd($var);
    }
}

/**
 * dump() with headers
 */
if (!function_exists('dumph')) {
    function dumph($var){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: *');
        dump($var);
    }
}
