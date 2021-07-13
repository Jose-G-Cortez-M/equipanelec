<?php

namespace App\Repository;

use App\Entity\Material;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Material|null find($id, $lockMode = null, $lockVersion = null)
 * @method Material|null findOneBy(array $criteria, array $orderBy = null)
 * @method Material[]    findAll()
 * @method Material[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository<Material>
 */
class MaterialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Material::class);
    }

    public function save(Material $material): Material
    {
        $this->getEntityManager()->persist($material);
        $this->getEntityManager()->flush();
        return $material;
    }

    public function reload(Material $material): Material
    {
        $this->getEntityManager()->refresh($material);
        return $material;
    }

    public function delete(Material $material)
    {
        $this->getEntityManager()->remove($material);
        $this->getEntityManager()->flush();
    }
 
}
