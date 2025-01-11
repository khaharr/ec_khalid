<?php
namespace App\Repository;

use App\Entity\BookRead;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BookReadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookRead::class);
    }

    // Méthode pour récupérer les livres lus d'un utilisateur
    public function findReadBooksByUserId(int $userId): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.user_id = :userId')
            ->andWhere('r.is_read = :isRead')
            ->setParameter('userId', $userId)
            ->setParameter('isRead', true) // Filtre pour les livres lus
            ->getQuery()
            ->getResult();
    }

    // Méthode pour récupérer tous les livres d'un utilisateur (qu'ils soient lus ou non)
    public function findByUserId(int $userId, bool $isRead = false): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.user_id = :userId')
            ->andWhere('r.is_read = :isRead')
            ->setParameter('userId', $userId)
            ->setParameter('isRead', $isRead) // Filtre selon l'état de lecture
            ->getQuery()
            ->getResult();
    }
}
