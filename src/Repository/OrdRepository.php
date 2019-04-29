<?php

namespace App\Repository;

use App\Entity\Ord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Ord|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ord|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ord[]    findAll()
 * @method Ord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ord::class);
    }
}
