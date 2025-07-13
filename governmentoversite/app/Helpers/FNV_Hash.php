<?php

namespace App\Helpers;

// Fowler-Noll-Vo (FNV) hash function
class FNV_Hash
{
    public static function Hash($str) {
        $hash = 2166136261; // FNV offset basis

        for ($i = 0; $i < strlen($str); $i++) {
            $hash = ($hash * 16777619) ^ ord($str[$i]); // FNV prime
        }

        return $hash;
    }   // end of Hash()
}   // end of FNV_Hash class
