<?php

namespace App\Controller;

use App\Repository\FlashcardRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for the home page
 */
class HomeController extends AbstractController
{
    /**
     * Displays the home page with user dashboard or welcome page
     * @param FlashcardRepository $flashcardRepository Repository to retrieve Flashcard entities
     * @param CategoryRepository $categoryRepository Repository to retrieve Category entities
     * @return Response Renders the home dashboard for authenticated users or welcome page for guests
     */
    #[Route('/', name: 'app_home')]
    public function index(FlashcardRepository $flashcardRepository, CategoryRepository $categoryRepository): Response
    {
        if (!$this->getUser()) {
            return $this->render('welcome.html.twig');
        }

        $flashcards = $flashcardRepository->findAll();
        $categories = $categoryRepository->findAll();
        
        return $this->render('home/index.html.twig', [
            'flashcards' => $flashcards,
            'categories' => $categories,
        ]);
    }
}