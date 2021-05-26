<?php

namespace App\Repository;

use App\Entity\DatasetTables;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DatasetTables|null find($id, $lockMode = null, $lockVersion = null)
 * @method DatasetTables|null findOneBy(array $criteria, array $orderBy = null)
 * @method DatasetTables[]    findAll()
 * @method DatasetTables[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DatasetTablesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DatasetTables::class);
    }

    // /**
    //  * @return DatasetTables[] Returns an array of DatasetTables objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DatasetTables
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
