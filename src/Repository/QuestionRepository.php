<?php

namespace App\Repository;

use App\Entity\Question;
use App\DTO\QuestionSearch;
use Doctrine\ORM\EntityRepository;


/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends EntityRepository
{
    public function findByQuestionSearch(QuestionSearch $dto)
    {
        //Define variable qui permet de creer une query
        $queryBuilder = $this->createQueryBuilder('ta');
        

        if(!empty($dto->search)){
            $queryBuilder ->andWhere(
                'ta.title like :search'
            );
            $queryBuilder->setParameter('search','%'.$dto->search. '%');
            
        }
          
        
         return $queryBuilder->getQuery()->execute();
    }

    public function findByDepartment(QuestionSearch $dto)
    {
      
        $queryBuilder = $this->createQueryBuilder('ta');
        

        if(!empty($dto->targetDepartment)){
            $queryBuilder ->andWhere(
                'ta.targetDepartment = :targetDepartment'
            );
            $queryBuilder->setParameter('targetDepartment','%'.$dto->targetDepartment. '%');
            
        }
          
        
         return $queryBuilder->getQuery()->execute();
    }

    public function findByQuestionDate($userId)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.user = :val')
            ->setParameter('val', $userId)
            ->orderBy('q.creationDate', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllByQuestionDate()
    {
        return $this->createQueryBuilder('q')
            ->orderBy('q.creationDate', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByDepartmentByQuestionDate($departmentId)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.targetDepartment = :val')
            ->setParameter('val', $departmentId)
            ->orderBy('q.creationDate', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }


//    /**
//     * @return Question[] Returns an array of Question objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Question
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
