<?php
namespace App\Controller;

use App\Repository\BookReadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
        $user = $this->getUser();

        if (!$user) {
            // Utilisation de la méthode redirectToRoute pour rediriger vers la page de connexion
            return $this->redirectToRoute('auth.login');
        }
        // Récupère toutes les lectures (pas uniquement celles lues)
        $allBooksRead = $this->readBookRepository->findByUserId($user->getId(), false);
        
        // Récupère uniquement les livres lus
        $booksReadCompleted = $this->readBookRepository->findReadBooksByUserId($user->getId());
    
        $email = $this->security->getUser()->getUserIdentifier();
        $books = $bookRepository->findAll();
        
        return $this->render('pages/home.html.twig', [
            'allBooksRead' => $allBooksRead, 
            'booksReadCompleted' => $booksReadCompleted, // Spécifique au composant
            'name' => 'Accueil',
            'email' => $email,
            'books' => $books,
        ]);
    }
}
