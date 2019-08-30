<?php

namespace App\Repository;

use App\Entity\LoginSocial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method LoginSocial|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoginSocial|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoginSocial[]    findAll()
 * @method LoginSocial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoginSocialRepository extends ServiceEntityRepository
{
    /**
     * LoginSocialRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LoginSocial::class);
    }

    /**
     * @param Criteria $criteria
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function getSocialAccount(Criteria $criteria)
    {
        return $this->createQueryBuilder('s')
            ->addCriteria($criteria)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
