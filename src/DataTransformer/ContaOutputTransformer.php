<?php

namespace App\DataTransformer;

use App\DTO\ContasDTO;
use App\DTO\MovimentacoesDTO;
use App\Entity\Conta;
use App\Entity\Movimentacao;
use App\Repository\MovimentacaoRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class ContaOutputTransformer
{
    public function __construct(
        private readonly MovimentacaoRepository $movimentacoesRepository
    )
    {
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    final public function transform(array $contas) : array
    {
        $this->output = [];

        /** @var Conta $conta */
        foreach ($contas as $conta) {
            $dto = new ContasDTO;
            $dto->conta = $conta->getConta();
            $dto->descricao = $conta->getDescricao();
            $dto->movimentacoes = $this->setMovimentacoes($dto,$conta);
            $dto->saldo = round($this->movimentacoesRepository->getSaldo($conta),2) ?? 0.00;
            $this->output[] = $dto;
        }

        return  $this->output;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function setMovimentacoes(ContasDTO $dto , Conta $conta): array
    {
        $arrayMovimentacoes = [];
        foreach ($conta->getMovimentacoes() as $movimentacao) {
            $movimentacaoDto = new MovimentacoesDTO();
            $movimentacaoDto->descricao = $movimentacao->getDescricao();
            $movimentacaoDto->dataMovimentacao = $movimentacao->getDataMovimentacao()->format('d/m/Y H:i');
            $movimentacaoDto->valor = $movimentacao->getValor();
            $movimentacaoDto->saldo = round($this->movimentacoesRepository->getSaldo($conta),2);
            $movimentacaoDto->conta = $conta->getConta();

            $arrayMovimentacoes[] = $movimentacaoDto;
        }

        return $arrayMovimentacoes;
    }
}