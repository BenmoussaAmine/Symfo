<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function active( $id ) {

        $conn = $this->getEntityManager()->getConnection();


        $sql = "UPDATE `user` SET `roles` = '[\"ROLE_USER\"]' WHERE (`id` = ".$id.")";

        $stmt = $conn->prepare($sql);
        return $stmt->execute();
    }

    public function desactive( $id ) {

        $conn = $this->getEntityManager()->getConnection();


        $sql = "UPDATE `user` SET `roles` = '[\"ROLE_NULL\"]' WHERE (`id` = ".$id.")";

        $stmt = $conn->prepare($sql);
        return $stmt->execute();
    }

    public function promote( $id ) {

        $conn = $this->getEntityManager()->getConnection();


        $sql = "UPDATE `user` SET `roles` = '[\"ROLE_ADMIN\"]' WHERE (`id` = ".$id.")";

        $stmt = $conn->prepare($sql);
       return $stmt->execute();
    }

    public function demote( $id ) {

        $conn = $this->getEntityManager()->getConnection();


        $sql = "UPDATE `user` SET `roles` = '[\"ROLE_USER\"]' WHERE (`id` = ".$id.")";

        $stmt = $conn->prepare($sql);
        return $stmt->execute();
    }


    public function change( $id , $pw ) {

        $conn = $this->getEntityManager()->getConnection();


        $sql = "UPDATE `symfo`.`user` SET `password` = '".$pw."' WHERE (`id` = '".$id."');";

        $stmt = $conn->prepare($sql);
        return $stmt->execute();
    }




    public function delete( $id ) {

        $conn = $this->getEntityManager()->getConnection();


        $sql = "DELETE FROM `user` WHERE (`id` = ".$id.")";

        $stmt = $conn->prepare($sql);
        return $stmt->execute();
    }

    public function getCountGov( ) {

        $conn = $this->getEntityManager()->getConnection();


        $sql = "select gouvernorat,
       sum(gouvernorat = 'Bizerte') as cnt_Bizerte,
       sum(gouvernorat = 'Tunis') as cnt_Tunis,
       sum(gouvernorat = 'Beja') as cnt_Beja,
        sum(gouvernorat = 'Jendouba') as cnt_Jendouba,
       sum(gouvernorat = 'Ariana') as cnt_Ariana,
         sum(gouvernorat = 'Manouba') as cnt_Manouba,
       sum(gouvernorat = 'Ben Arous') as cnt_BenArous,
        sum(gouvernorat = 'Zaghouan') as cnt_Zaghouan,
       sum(gouvernorat = 'Siliana') as cnt_Siliana,
        sum(gouvernorat = 'Kairouan') as cnt_Kairouan,
       sum(gouvernorat = 'LeKef') as cnt_LeKef,
        sum(gouvernorat = 'Nabeul') as cnt_Nabeul,
       sum(gouvernorat = 'Kasserine') as cnt_Kasserine,
        sum(gouvernorat = 'Sidi Bou Zid') as cnt_SidiBouZid,
       sum(gouvernorat = 'Sousse') as cnt_Sousse, 
       sum(gouvernorat = 'Monastir') as cnt_Monastir,
       sum(gouvernorat = 'Mahdia') as cnt_Mahdia,
       sum(gouvernorat = 'Sfax') as cnt_Sfax,
        sum(gouvernorat = 'Gafsa') as cnt_Gafsa,
       sum(gouvernorat = 'Touzeur') as cnt_Touzeur,
        sum(gouvernorat = 'Kebili') as cnt_Kebili,
        sum(gouvernorat = 'Gabes') as cnt_Gabes ,
       sum(gouvernorat = 'Medenine') as cnt_Medenine,
       sum(gouvernorat = 'Tataouine') as cnt_Tataouine
from symfo.user";


        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAllAssociative();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
