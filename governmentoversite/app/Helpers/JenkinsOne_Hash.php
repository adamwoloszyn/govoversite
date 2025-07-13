<?php

namespace App\Helpers;

// Jenkins One-at-a-Time hash function
class JenkinsOne_Hash
{
    public static function Hash($str) {
        $hash = 0;
        $len = strlen($str);

        for ($i = 0; $i < $len; $i++) {
          $hash += ord($str[$i]);
          $hash += ($hash << 10);
          $hash ^= ($hash >> 6);
        }

        $hash += ($hash << 3);
        $hash ^= ($hash >> 11);
        $hash += ($hash << 15);

        return $hash;
    }   // end of Hash()
}   // end of JenkinsOne_Hash class
