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

        $sql = " SELECT * FROM dataset WHERE nom like '%".$key."%'       ";
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


    public function merge( $tab1 , $tab2 , $queryCols , $tab1JoinCol , $tab2JoinCol  ) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "CREATE TABLE ".$tab1."_".$tab2."  AS 
  (SELECT 
			".$queryCols."
   FROM   ".$tab1." 
          INNER JOIN  ".$tab2."  
                  ON ".$tab1.".".$tab1JoinCol." =  ".$tab2.".".$tab2JoinCol.");
";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAllAssociative();
    }

    public function show( $dataset) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select * from dataset_tables_fields f , dataset_tables t , dataset d 
where f.id_dataset_table_id = t.id and t.id_dataset_id = d.id and d.id = ".$dataset." ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAllAssociative();
    }

    public function reset( $dataset) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "DELETE
FROM dataset_tables_fields
WHERE id_dataset_table_id IN
(
    SELECT id
    FROM dataset_tables
    WHERE id_dataset_id like ".$dataset."
) ;



";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAllAssociative();

    }


    public function delete( $dataset) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "DELETE
FROM dataset_tables_fields
WHERE id_dataset_table_id IN
(
    SELECT id
    FROM dataset_tables
    WHERE id_dataset_id like ".$dataset."
) ;

SET FOREIGN_KEY_CHECKS = 0; 

DELETE
FROM dataset_tables
WHERE id_dataset_id like ".$dataset."  ;

DELETE
FROM dataset
WHERE id like ".$dataset."  ;

SET FOREIGN_KEY_CHECKS = 1; 


SET SQL_SAFE_UPDATES = 1;

";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

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
