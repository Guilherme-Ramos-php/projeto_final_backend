<?php

namespace App\DataTransformer;

use App\DTO\ContasDTO;
use App\Entity\Conta;
use Doctrine\ORM\EntityManagerInterface;

class ContaDescricaoInputTransformer
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }
    public function transform(
        ContasDTO $contasDTO,
        Conta $conta
    ): Conta
    {
        return $this->setValues($contasDTO, $conta);
    }

    public function setValues(
        ContasDTO $contasDTO,
        Conta $conta
    ): Conta
    {
        if ($contasDTO->descricao) {

            $conta->setDescricao($contasDTO->descricao);
        }

        $this->entityManager->persist($conta);
        $this->entityManager->flush();

        return $conta;
    }
}