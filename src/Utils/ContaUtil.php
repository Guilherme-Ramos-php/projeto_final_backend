<?php

namespace App\Utils;

class ContaUtil
{

    public static function generateAccountNumber(int $caracteres = 11): string
    {
        $characters = '0123456789';
        $randomString = '';

        for ($i = 0; $i < $caracteres; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }
}