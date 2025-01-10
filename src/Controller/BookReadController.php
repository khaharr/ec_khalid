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
    #[Route('/add-book-read', name: 'book_read_add', methods: ['POST'])]
    public function addBookRead(Request $request, EntityManagerInterface $entityManager, BookRepository $bookRepository): JsonResponse
    {
        $bookId = $request->request->get('book');
        $description = $request->request->get('description');
        $rating = $request->request->get('rating');
        $isRead = $request->request->get('check') === 'on';

        $book = $bookRepository->find($bookId);
        if (!$book) {
            return $this->json(['error' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        $bookRead = new BookRead();
        $bookRead->setUserId($this->getUser()->getId());
        $bookRead->setBook($book);
        $bookRead->setDescription($description);
        $bookRead->setRating($rating);
        $bookRead->setRead($isRead);
        $bookRead->setCreatedAt(new \DateTime());
        $bookRead->setUpdatedAt(new \DateTime());

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
}
