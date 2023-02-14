<?php


use Hashids\Hashids;

if (!function_exists('encodeId')) {
    function encodeId($id): string
    {
        $hash = new Hashids(config('7dab.salt'), 10);
        
        return $hash->encode($id);
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
