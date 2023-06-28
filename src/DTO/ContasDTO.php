<?php

namespace App\DTO;

use App\Entity\Pessoa;
use App\Validator\UniqueContaValidator\UniqueConta;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
class ContasDTO
{

    #[Assert\NotBlank]
    #[Assert\Length(11)]
    #[UniqueConta(groups: ['post'])]
    public string $conta;

    #[Assert\NotBlank(groups: ['post'])]
    #[Assert\Length(200)]
    public string $descricao;
    public array $movimentacoes = [];


    public float $saldo;

    #[Assert\NotBlank]
    public int $id_pessoa;
}