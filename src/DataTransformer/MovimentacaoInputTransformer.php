<?php

namespace App\DataTransformer;

use App\DTO\MovimentacoesDTO;
use App\Entity\Movimentacao;
use App\Repository\ContaRepository;
use App\Repository\MovimentacaoRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class MovimentacaoInputTransformer
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ContaRepository $contasRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function transform(
        MovimentacoesDTO $movimentacoesDTO
    ): Movimentacao
    {
        $movimentacoes = new Movimentacao();
        return $this->setValues($movimentacoesDTO, $movimentacoes);
    }

    /**
     * @throws Exception
     */
    public function setValues(
        MovimentacoesDTO $movimentacoesDTO,
        Movimentacao $movimentacoes
    ): Movimentacao
    {
        $conta = $this->contasRepository->findOneBy(['conta'=>$movimentacoesDTO->conta]);

        if (!$conta) {
            throw new Exception('Conta Inexistente');
        }

        if ($movimentacoesDTO->conta) {
            $movimentacoes->setConta($conta);
        }

        if ($movimentacoesDTO->acao) {
            $movimentacoes->setAcao($movimentacoesDTO->acao);
        }
        if ($movimentacoesDTO->descricao) {
            $movimentacoes->setDescricao($movimentacoesDTO->descricao);
        }

        if ($movimentacoesDTO->valor) {
            $movimentacoes->setValor(round($movimentacoesDTO->valor,2));
        }

        $movimentacoes->setDataMovimentacao(new \DateTime());

        $this->entityManager->persist($movimentacoes);
        $this->entityManager->flush();

        return $movimentacoes;
    }
}