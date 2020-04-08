<?php

namespace App\Repository;

use App\Entity\Preferene;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Preferene|null find($id, $lockMode = null, $lockVersion = null)
 * @method Preferene|null findOneBy(array $criteria, array $orderBy = null)
 * @method Preferene[]    findAll()
 * @method Preferene[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrefereneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Preferene::class);
    }

    // /**
    //  * @return Preferene[] Returns an array of Preferene objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Preferene
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
