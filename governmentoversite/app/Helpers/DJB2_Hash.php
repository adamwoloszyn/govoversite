<?php

namespace App\Helpers;

// DJB2: This is a simple hash function that was created by Dan Bernstein
class DJB2_Hash
{
    public static function Hash($str) {
        $hash = 5381;
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
          $hash = (($hash << 5) + $hash) + ord($str[$i]);
        }
        return $hash;
    }   // end of Hash()
}   // end of class DJB2_Hash

