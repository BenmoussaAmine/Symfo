<?php

namespace App\Repository;

use App\Entity\Dataset;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dataset|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dataset|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dataset[]    findAll()
 * @method Dataset[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DatasetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dataset::class);
    }

    public function listDatasets( ) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT nom FROM dataset; ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([]);

        return $stmt->fetchAllAssociative();
    }

    public function createDataset( String  $nom ) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "INSERT INTO `dataset` (`nom`) VALUES (:nom);";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['nom' => $nom]);

        return $stmt->fetchAllAssociative();
    }

    public function addToDataset(   $dataset ,  $tables ) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "UPDATE `dataset` SET `tables` = :tabs WHERE (`nom` = :dataset);
";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['tabs' => $tables , 'dataset' => $dataset]);

        return $stmt->fetchAllAssociative();
    }

    public function findByName( String  $dataset  ) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * FROM symfo.dataset where nom =  :dataset";
        $stmt = $conn->prepare($sql);
        $stmt->execute([ "dataset" =>$dataset]);

        return $stmt->fetchAllAssociative();
    }

    public function filterByKey( $key ) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * FROM dataset WHERE nom like '%".$key."%'       ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([]);

        return $stmt->fetchAllAssociative();
    }

    public function getTables( $dataset ) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT tables FROM dataset where nom = '".$dataset."'";
        $stmt = $conn->prepare($sql);
        $stmt->execute([]);

        return $stmt->fetchAllAssociative();
    }




    // /**
    //  * @return Dataset[] Returns an array of Dataset objects
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
    public function findOneBySomeField($value): ?Dataset
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
