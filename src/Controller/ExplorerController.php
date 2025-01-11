<?php

namespace App\Controller;

use App\Entity\BookRead;
use App\Entity\Comment;
use App\Entity\Like;
use App\Repository\BookReadRepository;
use App\Repository\CommentRepository;
use App\Repository\BookRepository;
use App\Repository\LikeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExplorerController extends AbstractController
{
    #[Route('/explorer', name: 'explorer_index', methods: ['GET'])]
    public function index(BookReadRepository $bookReadRepository, BookRepository $bookRepository)
    {
        // Récupérer  lives déjà lus
        $booksRead = $bookReadRepository->findBy(['is_read' => true]);

        // Récupérer tous les livres 
        $books = $bookRepository->findAll();

        return $this->render('pages/explorer.html.twig', [
            'booksRead' => $booksRead,
            'books' => $books, 
        ]);
    }
    
    #[Route('/explorer/{id}', name: 'explorer_show', methods: ['GET'])]
    public function show(BookRead $bookRead)
    {
        return $this->render('pages/show.html.twig', [
            'bookRead' => $bookRead,
        ]);
    }


   #[Route('/api/like/{id}', name: 'like_book_read', methods: ['POST'])]
public function likeBookRead(BookRead $bookRead, LikeRepository $likeRepository): JsonResponse
{
    try {
        $userId = $this->getUser()->getId();

        // Vérifiecation si l'utilisateur a déjà liké ce livre
        $existingLike = $likeRepository->findOneBy([
            'bookRead' => $bookRead,
            'userId' => $userId,
        ]);

        if (!$existingLike) {
            $like = new Like();
            $like->setBookRead($bookRead);
            $like->setUserId($userId);
            $likeRepository->save($like, true);
        }

        // Compter les likes après modification
        $likeCount = $likeRepository->count(['bookRead' => $bookRead]);

        return $this->json(['success' => true, 'likeCount' => $likeCount]);

    } catch (\Exception $e) {
        return $this->json(['success' => false, 'message' => 'Erreur lors de l\'ajout du like.', 'error' => $e->getMessage()], 500);
    }
}

   


    #[Route('/api/comment/{id}', name: 'comment_book_read', methods: ['POST'])]
    public function commentBookRead(BookRead $bookRead, Request $request, CommentRepository $commentRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $userId = $this->getUser()->getId();

        $comment = new Comment();
        $comment->setBookRead($bookRead);
        $comment->setContent($data['comment']);
        $comment->setUserId($userId);
        $comment->setCreatedAt(new \DateTime());

        $commentRepository->save($comment, true);

        return $this->json(['success' => true, 'comment' => $data['comment'], 'userId' => $userId]);
    }
} 