<?php

namespace App\DTO;

use DateTime;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class MovimentacoesDTO
{
    #[Assert\NotBlank]
    public $conta;

    #[Assert\NotBlank]
    public string $acao;

    #[Assert\NotBlank]
    public string $descricao;

    #[Assert\NotBlank]
    public float $valor;

    public DateTimeInterface $dataMovimentacao;
}