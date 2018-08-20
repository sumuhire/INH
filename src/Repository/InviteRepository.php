<?php

namespace App\Repository;

use App\Entity\Invite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityRepository;
use App\DTO\InviteSearch;

/**
 * @method Invite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invite[]    findAll()
 * @method Invite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InviteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Invite::class);
    }

    /**
     * @return Invite[] Returns an array of Invite objects
     */

    public function findByEmail(InviteSearch $value)
    {
        $queryBuilder = $this->createQueryBuilder('invite');
        if (!empty($value->search)) {

            $queryBuilder->andWhere('invite.email like :val');
            $queryBuilder->setParameter('val', '%' . $value->getSearch() . '%');
        }

        return $queryBuilder->getQuery()->execute();

    }
}
