<?php

namespace App\Helpers;

class FormattedResponse {

   public static function instance($response = "False", $data = null){
    
    $formatted_response = new \stdClass();
    $formatted_response->Response = $response;
    $formatted_response->Search = $data;

    return $formatted_response;

   }

   public static function isUndef($obj, $prop){
    try{
        if($obj->$prop){
            return false;
        }
    } catch (\Exception $e){
        return true;
    }
   }

}