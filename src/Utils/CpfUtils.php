<?php

namespace App\Utils;

class CpfUtils
{
    public static function formatCpf($cpf)
    {
        return preg_replace('/\D/', '', $cpf);
    }
}