<?php

namespace App\Controller;

use App\Repository\FlashcardRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(FlashcardRepository $flashcardRepository, CategoryRepository $categoryRepository): Response
    {
        // Jeśli użytkownik nie jest zalogowany, pokaż stronę login/register
        if (!$this->getUser()) {
            return $this->render('welcome.html.twig');
        }
        
        // Jeśli zalogowany, pokaż aplikację
        $flashcards = $flashcardRepository->findAll();
        $categories = $categoryRepository->findAll();
        
        return $this->render('home/index.html.twig', [
            'flashcards' => $flashcards,
            'categories' => $categories,
        ]);
    }
}