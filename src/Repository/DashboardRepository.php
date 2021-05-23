<?php

namespace App\Repository;

use App\Entity\Dashboard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dashboard|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dashboard|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dashboard[]    findAll()
 * @method Dashboard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DashboardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dashboard::class);
    }


    /**
     * @return Assure[] Returns an array of Articles objects
     */
    public function apiFind() : array
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a.id', 'a.sexe');

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function listTables2(EntityManagerInterface $em) {
        $allMetadata = $em->getMetadataFactory()->getAllMetadata();
        $tableNames = array_map(
            function(ClassMetadata $meta) {
                return $meta->getTableName();
            },
            $allMetadata);

        return $tableNames;
        // do something with the table names
    }

    public function listColumns2(EntityManagerInterface $em   ) {
        $class = $em->getClassMetadata(Assure::class);
        $fields = [];
        if (!empty($class->discriminatorColumn)) {
            $fields[] = $class->discriminatorColumn['name'];
        }
        $fields = array_merge($class->getColumnNames(), $fields);
        foreach ($fields as $index => $field) {
            if ($class->isInheritedField($field)) {
                unset($fields[$index]);
            }
        }
        foreach ($class->getAssociationMappings() as $name => $relation) {
            if (!$class->isInheritedAssociation($name)){
                foreach ($relation['joinColumns'] as $joinColumn) {
                    $fields[] = $joinColumn['name'];
                }
            }
        }
        return $fields;
    }

    public function listColumns( String  $tab ) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = :tab ";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['tab' => $tab]);

        return $stmt->fetchAllAssociative();
    }

    public function listTables(EntityManagerInterface $em  ) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT table_name FROM information_schema.tables
WHERE table_schema = 'symfo' AND table_name <> 'doctrine_migration_versions' AND table_name <> 'user'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAllAssociative();
    }



    public function pieChartQuery(  $tab ,  $col ) {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT ".$col.",
                COUNT(*) AS `cnt`
                FROM
                ".$tab."
                GROUP BY
                ".$col."
                ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([ 'col' => $col , 'tab' => $tab  ]);
        return $stmt->fetchAllAssociative();
    }

    public function barChartQuery( $tab ,  $col1 , $col2 ) {

        $conn = $this->getEntityManager()->getConnection();


        $sql = "SELECT  ".$col1." ,".$col2." 
                FROM
                ".$tab."
                GROUP BY
                ".$col1."
                ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAllAssociative();
    }

    public function barChartQuery2( $tab ,  $col1 , $col2 , $query) {

        $conn = $this->getEntityManager()->getConnection();


        $sql = "select ".$col2.",
       ".$query."
from ".$tab."
group by ".$col2." order by ".$col2." asc;";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAllAssociative();
    }

    public function lineChartQuery( $tab ,  $col1 , $col2 ) {

        $conn = $this->getEntityManager()->getConnection();


        $sql = "SELECT  ".$col1." ,".$col2." 
                FROM
                ".$tab."
                GROUP BY
                ".$col1."
                ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAllAssociative();
    }
    public function getUniques( $tab ,  $col1  ) {

        $conn = $this->getEntityManager()->getConnection();


        $sql = "SELECT DISTINCT  ".$col1."
FROM ".$tab.";";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAllAssociative();
    }


    // /**
    //  * @return Assure[] Returns an array of Assure objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Assure
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    // /**
    //  * @return Dashboard[] Returns an array of Dashboard objects
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
    public function findOneBySomeField($value): ?Dashboard
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
