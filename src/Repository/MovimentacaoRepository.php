<?php

namespace App\Repository;

use App\Entity\Conta;
use App\Entity\Movimentacao;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movimentacao>
 *
 * @method Movimentacao|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movimentacao|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movimentacao[]    findAll()
 * @method Movimentacao[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovimentacaoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movimentacao::class);
    }

    public function save(Movimentacao $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Movimentacao $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function getMovimentacoes(Conta $conta) :array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.conta = :conta')
            ->setParameter('conta', $conta->getId())
            ->orderBy('c.dataMovimentacao', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getSaldo(Conta $conta) :string |null
    {
        return $this->createQueryBuilder('c')
            ->select('SUM(c.valor) ')
            ->andWhere('c.conta = :conta')
            ->setParameter('conta', $conta->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }
}
