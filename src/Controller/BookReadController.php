<?php

namespace App\Controller;

use App\Entity\BookRead;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class BookReadController extends AbstractController
{
    // Route pour ajouter une lecture d'un livre
    #[Route('/add-book-read', name: 'book_read_add', methods: ['POST'])]
    public function addBookRead(Request $request, EntityManagerInterface $entityManager, BookRepository $bookRepository): JsonResponse
    {
        // Récupération des données envoyées via la requête POST
        $bookId = $request->request->get('book');
        $description = $request->request->get('description');
        $rating = $request->request->get('rating');
        $isRead = $request->request->get('check') === 'on';

        // Recherche du livre en base de données
        $book = $bookRepository->find($bookId);
        if (!$book) {
            return $this->json(['error' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        // Création d'une nouvelle instance de BookRead pour enregistrer la lecture
        $bookRead = new BookRead();
        $bookRead->setUserId($this->getUser()->getId()); 
        $bookRead->setBook($book); 
        $bookRead->setDescription($description); 
        $bookRead->setRating($rating); 
        $bookRead->setRead($isRead); // Statut "lu" ou "non lu"
        $bookRead->setCreatedAt(new \DateTime()); // Date de création
        $bookRead->setUpdatedAt(new \DateTime()); // Date de mise à jour initiale

        $entityManager->persist($bookRead);
        $entityManager->flush(); 

        return $this->json([
            'id' => $bookRead->getId(),
            'bookName' => $book->getName(),
            'description' => $description,
            'rating' => $rating,
            'isRead' => $isRead
        ], Response::HTTP_OK);
    }

    // Route pour modifier une lecture existante
    #[Route('/edit-book-read/{id}', name: 'book_read_edit', methods: ['POST'])]
    public function editBookRead(Request $request, EntityManagerInterface $entityManager, BookRepository $bookRepository, BookRead $bookRead): JsonResponse
    {
        
        $bookId = $request->request->get('book');
        $description = $request->request->get('description');
        $rating = $request->request->get('rating');
        $isRead = $request->request->get('check') === 'on';

        // Recherche du livre en bdd
        $book = $bookRepository->find($bookId);
        if (!$book) {
            return $this->json(['error' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

       
        $bookRead->setBook($book); 
        $bookRead->setDescription($description); 
        $bookRead->setRating($rating); 
        $bookRead->setRead($isRead); // Nouveau statut "lu"
        $bookRead->setUpdatedAt(new \DateTime()); 

        $entityManager->flush();

        return $this->json([
            'id' => $bookRead->getId(),
            'bookName' => $book->getName(),
            'description' => $description,
            'rating' => $rating,
            'isRead' => $isRead,
        ], Response::HTTP_OK);
    }

    // Route pour récupérer les informations d'une lecture spécifique
    #[Route('/api/book-read/{id}', name: 'book_read_get', methods: ['GET'])]
    public function getBookRead(BookRead $bookRead): JsonResponse
    {
        
        return $this->json([
            'id' => $bookRead->getId(),
            'bookId' => $bookRead->getBook()->getId(),
            'description' => $bookRead->getDescription(),
            'rating' => $bookRead->getRating(),
            'isRead' => $bookRead->isRead(),
        ]);
    }
}
