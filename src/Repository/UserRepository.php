<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\DTO\UserSearch;
use Doctrine\ORM\EntityRepository;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends EntityRepository
{
   

   /**
    * @return User[] Returns an array of User objects
   */
    
    public function findByEmail(UserSearch $value)
    {
        $queryBuilder = $this->createQueryBuilder('u');
        if (!empty($value->search)) {

            $queryBuilder->andWhere('u.email like :val');
            $queryBuilder->setParameter('val', '%' . $value->getSearch() . '%');
        }
        

        return $queryBuilder->getQuery()->execute();

    }

    public function findByUsername($value)
    {
        $name = $this->createQueryBuilder('u')
            ->andWhere('u.username like :val')
            ->setParameter('val', '%' . $value->getSearch() . '%')
            ->getQuery()
        ;
        return $name->execute();
    }

    public function findByName($value, $value2)
    {
            $name = $this->createQueryBuilder('u')
            ->andWhere('u.firstname like :val')
            ->andWhere('u.lastname like :val2')
            ->setParameter('val',$value->getSearch())
            ->setParameter('val2', $value2->getSearch())
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
        ;
        return $name->execute();
    }

    public function findByFirstName($value) {

        $name = $this->createQueryBuilder('u')
            ->andWhere('u.firstname like :val')
            ->setParameter('val', '%' . $value->getSearch() . '%')
            ->orderBy('u.id', 'ASC')
            ->getQuery();
        return $name->execute();
    }
    public function findByLastName($value) {

        $name = $this->createQueryBuilder('u')
            ->andWhere('u.lastname like :val')
            ->setParameter('val', '%' . $value->getSearch() . '%')
            ->orderBy('u.id', 'ASC')
            ->getQuery();
        return $name->execute();
    }

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
