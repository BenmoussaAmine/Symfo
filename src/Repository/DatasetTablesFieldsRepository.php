<?php

namespace App\Repository;

use App\Entity\DatasetTablesFields;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DatasetTablesFields|null find($id, $lockMode = null, $lockVersion = null)
 * @method DatasetTablesFields|null findOneBy(array $criteria, array $orderBy = null)
 * @method DatasetTablesFields[]    findAll()
 * @method DatasetTablesFields[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DatasetTablesFieldsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DatasetTablesFields::class);
    }

    // /**
    //  * @return DatasetTablesFields[] Returns an array of DatasetTablesFields objects
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
    public function findOneBySomeField($value): ?DatasetTablesFields
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
