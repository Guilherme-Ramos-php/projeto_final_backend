<?php

namespace App\Entity;

use App\Repository\ContaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

#[ORM\Entity(repositoryClass: ContaRepository::class)]
class Conta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_conta')]
    private ?int $id = null;

    #[ORM\Column(length: 11)]
    private ?string $conta = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Serializer\Type("DateTimeInterface<'d/m/yy H:i' >")]
    private ?\DateTimeInterface $dataCriacao = null;

    #[Serializer\Exclude]
    #[ORM\ManyToOne(targetEntity: Pessoa::class, inversedBy: 'contas')]
    #[ORM\JoinColumn(referencedColumnName: 'id_pessoa', nullable: false)]
    private ?Pessoa $pessoa = null;

    #[Serializer\Exclude]
    #[ORM\OneToMany(mappedBy: 'conta', targetEntity: Movimentacao::class)]
    private Collection $movimentacoes;

    #[ORM\Column(length: 200)]
    private ?string $descricao = null;

    public function __construct()
    {
        $this->movimentacoes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConta(): ?string
    {
        return $this->conta;
    }

    public function setConta(string $conta): static
    {
        $this->conta = $conta;

        return $this;
    }

    public function getDataCriacao(): ?\DateTimeInterface
    {
        return $this->dataCriacao;
    }

    public function setDataCriacao(\DateTimeInterface $dataCriacao): static
    {
        $this->dataCriacao = $dataCriacao;

        return $this;
    }

    public function getPessoa(): ?Pessoa
    {
        return $this->pessoa;
    }

    public function setPessoa(?Pessoa $pessoa): static
    {
        $this->pessoa = $pessoa;

        return $this;
    }

    /**
     * @return Collection<int, Movimentacao>
     */
    public function getMovimentacoes(): Collection
    {
        return $this->movimentacoes;
    }

    public function addMovimentacao(Movimentacao $movimentacao): static
    {
        if (!$this->movimentacoes->contains($movimentacao)) {
            $this->movimentacoes->add($movimentacao);
            $movimentacao->setConta($this);
        }

        return $this;
    }

    public function removeMovimentacao(Movimentacao $movimentacao): static
    {
        if ($this->movimentacoes->removeElement($movimentacao)) {
            // set the owning side to null (unless already changed)
            if ($movimentacao->getConta() === $this) {
                $movimentacao->setConta(null);
            }
        }

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
