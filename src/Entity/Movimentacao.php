<?php

namespace App\Entity;

use App\Repository\MovimentacaoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

#[ORM\Entity(repositoryClass: MovimentacaoRepository::class)]
class Movimentacao
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Serializer\Exclude]
    #[ORM\ManyToOne(targetEntity: Conta::class, inversedBy: 'movimentacoes')]
    #[ORM\JoinColumn(referencedColumnName: 'id_conta', nullable: false)]
    private ?Conta $conta = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Serializer\Type("DateTimeInterface<'d/m/yy H:i' >")]
    private ?\DateTimeInterface $dataMovimentacao = null;

    #[ORM\Column(length: 9)]
    private ?string $acao = null;

    #[ORM\Column]
    private ?float $valor = null;

    #[ORM\Column(length: 200)]
    private ?string $descricao = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConta(): ?Conta
    {
        return $this->conta;
    }

    public function setConta(?Conta $conta): self
    {
        $this->conta = $conta;

        return $this;
    }

    public function getDataMovimentacao(): ?\DateTimeInterface
    {
        return $this->dataMovimentacao;
    }

    public function setDataMovimentacao(\DateTimeInterface $dataMovimentacao): self
    {
        $this->dataMovimentacao = $dataMovimentacao;

        return $this;
    }

    public function getAcao(): ?string
    {
        return $this->acao;
    }

    public function setAcao(string $acao): self
    {
        $this->acao = $acao;

        return $this;
    }

    public function getValor(): ?float
    {
        return $this->valor;
    }

    public function setValor(float $valor): self
    {
        $this->valor = $valor;

        return $this;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): static
    {
        $this->descricao = $descricao;

        return $this;
    }
}
