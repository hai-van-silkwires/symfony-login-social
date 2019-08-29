<?php

namespace App\Repository;

use App\Entity\Facebook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Facebook|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facebook|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facebook[]    findAll()
 * @method Facebook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacebookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facebook::class);
    }

    public function execute(Facebook $facebook): Facebook
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($facebook);
        $entityManager->flush();

        return $facebook;
    }
}
