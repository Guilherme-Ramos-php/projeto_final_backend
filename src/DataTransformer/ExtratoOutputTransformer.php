<?php

namespace App\DataTransformer;

use App\DTO\ContasDTO;
use App\DTO\MovimentacoesDTO;
use App\Entity\Conta;
use App\Entity\Movimentacao;
use App\Repository\MovimentacaoRepository;

class ExtratoOutputTransformer
{
    private ContasDTO $output;

    public function __construct(
        private readonly MovimentacaoRepository $movimentacoesRepository,
    ) {
    }

    final public function transform(Conta $conta) : ContasDTO
    {

        $this->output = new ContasDTO;
        $this->output->movimentacoes = $this->setMovimentacoes($conta);

        return  $this->output;
    }

    public function setMovimentacoes(Conta $conta):array
    {
        $arrayMovimentacoes = [];
        $saldoTotal = 0;
        $movimentacoes = $this->movimentacoesRepository->getMovimentacoes($conta);

        if ($movimentacoes) {
            /** @var Movimentacao $movimentacao */
            foreach ($movimentacoes as $movimentacao) {
                $movimentacaoDTO = new MovimentacoesDTO();
                $movimentacaoDTO->dataMovimentacao = $movimentacao->getDataMovimentacao();
                $movimentacaoDTO->valor = $movimentacao->getValor();
                $saldoTotal += $movimentacao->getValor();
                $arrayMovimentacoes[] = $movimentacaoDTO;
            }
        }
        $this->output->saldo = $saldoTotal;
        return $arrayMovimentacoes;
    }
}