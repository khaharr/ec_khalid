<?php

namespace App\Controller;

use App\Repository\BookReadRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use function Symfony\Component\String\s;
use App\Repository\BookRepository;

class HomeController extends AbstractController
{
    private BookReadRepository $readBookRepository;

    // Inject the repository via the constructor
    public function __construct(BookReadRepository $bookReadRepository, private readonly Security $security)
    {
        $this->readBookRepository = $bookReadRepository;
    }

    #[Route('/', name: 'app.home')]
    public function index(BookRepository $bookRepository): Response
    {
        $user = $this->getUser ();
        $booksRead  = $this->readBookRepository->findByUserId($user->getId(), false);
        $email = $this->security->getUser()->getUserIdentifier();
        $books = $bookRepository->findAll();



        // Render the 'hello.html.twig' template
        return $this->render('pages/home.html.twig', [
            'booksRead' => $booksRead,
            'name'      => 'Accueil', // Pass data to the view
            'email'     => $email,
            'books' => $books,

        ]);
    }
    
}
