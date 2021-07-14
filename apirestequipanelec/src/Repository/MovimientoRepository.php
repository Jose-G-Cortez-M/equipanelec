<?php

namespace App\Repository;

use App\Entity\Movimiento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movimiento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movimiento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movimiento[]    findAll()
 * @method Movimiento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovimientoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movimiento::class);
    }

    public function save(Movimiento $movimiento): Movimiento
    {
        $this->getEntityManager()->persist($movimiento);
        $this->getEntityManager()->flush();
        return $movimiento;
    }

    public function reload(Movimiento $movimiento): Movimiento
    {
        $this->getEntityManager()->refresh($movimiento);
        return $movimiento;
    }

    public function delete(Movimiento $movimiento)
    {
        $this->getEntityManager()->remove($movimiento);
        $this->getEntityManager()->flush();
    }
}
