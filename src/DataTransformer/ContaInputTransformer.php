<?php

namespace App\DataTransformer;

use App\DTO\ContasDTO;
use App\Entity\Conta;
use App\Entity\Pessoa;
use App\Utils\ContaUtil;
use Doctrine\ORM\EntityManagerInterface;

class ContaInputTransformer
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function transform(
        ContasDTO $contasDTO,
    ): Conta
    {
        $conta = new Conta();
        return $this->setValues($contasDTO, $conta);
    }

    public function setValues(
        ContasDTO $contasDTO,
        Conta $conta
    ): Conta
    {
        $accountNumber = $contasDTO->conta;

        while ($this->entityManager->getRepository(Conta::class)->findOneBy(['conta'=>$accountNumber])?->getConta() == $accountNumber)
        {
            $accountNumber = ContaUtil::generateAccountNumber();
        }

        if ($contasDTO->conta) {

            $conta->setConta($contasDTO->conta);
        }

        if ($contasDTO->descricao) {

            $conta->setDescricao($contasDTO->descricao);
        }

        if ($contasDTO->id_pessoa) {
            $conta->setPessoa($this->entityManager->getRepository(Pessoa::class)->find($contasDTO->id_pessoa));
        }

        $conta->setDataCriacao(new \DateTime());

        $this->entityManager->persist($conta);
        $this->entityManager->flush();

        return $conta;
    }
}