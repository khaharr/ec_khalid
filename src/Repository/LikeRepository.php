<?php

namespace App\Repository;

use App\Entity\Like;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Like::class);
    }

    public function save(Like $like, bool $flush = false): void
    {
        $this->getEntityManager()->persist($like);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
