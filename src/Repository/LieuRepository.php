<?php

namespace App\Repository;

use App\Entity\Lieu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Lieu>
 */
class LieuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lieu::class);
    }

    /**
     * @return Lieu[]
     */
    public function findByVille(string $ville): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.ville = :ville')
            ->setParameter('ville', $ville)
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Lieu[]
     */
    public function findByVilleLike(string $villeLike): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('LOWER(l.ville) LIKE LOWER(:pattern)')
            ->setParameter('pattern', '%' . $villeLike . '%')
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Lieu[] Returns an array of Lieu objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Lieu
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
