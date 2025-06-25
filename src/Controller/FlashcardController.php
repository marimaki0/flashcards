<?php

namespace App\Controller;

use App\Entity\Flashcard;
use App\Form\FlashcardForm;
use App\Repository\FlashcardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controller for managing flashcard operations
 * Handles CRUD operations for flashcards with category filtering and user security
 */
#[Route('/flashcard')]
#[IsGranted('ROLE_USER')]
final class FlashcardController extends AbstractController
{
    /**
     * Lists all user flashcards with optional category filtering
     * @param Request $request HTTP request containing filter parameters
     * @param FlashcardRepository $flashcardRepository Repository to retrieve Flashcard entities
     * @param EntityManagerInterface $entityManager Entity manager for database operations
     * @return Response Renders the index view with filtered flashcards
     */
    #[Route('', name: 'app_flashcard_index', methods: ['GET'])]
    public function index(Request $request, FlashcardRepository $flashcardRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $categoryId = $request->query->get('category');
        
        $categories = $entityManager->getRepository(\App\Entity\Category::class)->findAll();
        
        if ($categoryId && $categoryId !== 'all') {
            $flashcards = $flashcardRepository->findBy([
                'user' => $user,
                'category' => $categoryId
            ]);
            $selectedCategory = $entityManager->getRepository(\App\Entity\Category::class)->find($categoryId);
            $selectedCategoryName = $selectedCategory ? $selectedCategory->getName() : 'All';
        } else {
            $flashcards = $flashcardRepository->findBy(['user' => $user]);
            $selectedCategoryName = 'All';
            $categoryId = 'all';
        }
        
        return $this->render('flashcard/index.html.twig', [
            'flashcards' => $flashcards,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
            'selectedCategoryName' => $selectedCategoryName,
        ]);
    }

    /**
     * Creates a new flashcard
     * @param Request $request HTTP request containing form data
     * @param EntityManagerInterface $entityManager Entity manager for persisting data
     * @return Response Renders the new form or redirects after successful creation
     */
    #[Route('/new', name: 'app_flashcard_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $flashcard = new Flashcard();
        $form = $this->createForm(FlashcardForm::class, $flashcard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $flashcard->setUser($this->getUser());
            $entityManager->persist($flashcard);
            $entityManager->flush();

            return $this->redirectToRoute('app_flashcard_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('flashcard/new.html.twig', [
            'flashcard' => $flashcard,
            'form' => $form,
        ]);
    }

    /**
     * Shows details of a specific flashcard
     * @param Flashcard $flashcard The flashcard entity to display
     * @return Response Renders the show view with flashcard details
     * @throws AccessDeniedException When user tries to access flashcard they don't own
     */
    #[Route('/show/{id}', name: 'app_flashcard_show', methods: ['GET'])]
    public function show(Flashcard $flashcard): Response
    {
        if ($flashcard->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only view your own flashcards.');
        }

        return $this->render('flashcard/show.html.twig', [
            'flashcard' => $flashcard,
        ]);
    }

    /**
     * Edits an existing flashcard
     * @param Request $request HTTP request containing form data
     * @param Flashcard $flashcard The flashcard entity to edit
     * @param EntityManagerInterface $entityManager Entity manager for persisting changes
     * @return Response Renders the edit form or redirects after successful update
     * @throws AccessDeniedException When user tries to edit flashcard they don't own
     */
    #[Route('/edit/{id}', name: 'app_flashcard_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Flashcard $flashcard, EntityManagerInterface $entityManager): Response
    {
        if ($flashcard->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only edit your own flashcards.');
        }

        $form = $this->createForm(FlashcardForm::class, $flashcard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_flashcard_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('flashcard/edit.html.twig', [
            'flashcard' => $flashcard,
            'form' => $form,
        ]);
    }

    /**
     * Deletes a flashcard
     * @param Request $request HTTP request containing CSRF token
     * @param Flashcard $flashcard The flashcard entity to delete
     * @param EntityManagerInterface $entityManager Entity manager for removing data
     * @return Response Redirects to the flashcard index after deletion
     * @throws AccessDeniedException When user tries to delete flashcard they don't own
     */
    #[Route('/delete/{id}', name: 'app_flashcard_delete', methods: ['POST'])]
    public function delete(Request $request, Flashcard $flashcard, EntityManagerInterface $entityManager): Response
    {
        if ($flashcard->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only delete your own flashcards.');
        }

        if ($this->isCsrfTokenValid('delete'.$flashcard->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($flashcard);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_flashcard_index', [], Response::HTTP_SEE_OTHER);
    }
}