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
