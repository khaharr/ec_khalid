<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function countBooksReadByCategory(): array
    {
        try {
            return $this->createQueryBuilder('c')
                ->select('c.name AS category, COUNT(b.id) AS bookReadCount')
                ->leftJoin('c.books', 'b')
                ->groupBy('c.id')
                ->getQuery()
                ->getResult();
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de l'exécution de la requête : " . $e->getMessage());
        }
    }
    
    
}
