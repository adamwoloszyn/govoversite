<?php

namespace App\Helpers;

// DJB2: This is a simple hash function that was created by Dan Bernstein
class Unique_FileName
{
    public static function CreateUniqueFileName($prefix, $desiredExtension) {
        return $prefix . '-' . date('Y_m_d__H_i_s') . '-' . time() . '-' . mt_rand() . '.' . $desiredExtension;
    }   // end of Hash()
}   // end of class Unique_FileName