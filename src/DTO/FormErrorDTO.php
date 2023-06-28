<?php

namespace App\DTO;

class FormErrorDTO
{
    public function __construct($_status, $_errorMsg, $_errorReport)
    {
        $this->status = $_status;
        $this->errorMsg = $_errorMsg;
        $this->errorReport = $_errorReport;
    }

    public int $status;

    public string $errorMsg;

    public array $errorReport = [];
}