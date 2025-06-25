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


#[Route('/flashcard')]
#[IsGranted('ROLE_USER')]
final class FlashcardController extends AbstractController
{
    #[Route(name: 'app_flashcard_index', methods: ['GET'])]
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

    #[Route('/{id}', name: 'app_flashcard_show', methods: ['GET'])]
    public function show(Flashcard $flashcard): Response
    {
        if ($flashcard->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only view your own flashcards.');
        }

        return $this->render('flashcard/show.html.twig', [
            'flashcard' => $flashcard,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_flashcard_edit', methods: ['GET', 'POST'])]
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

    #[Route('/{id}', name: 'app_flashcard_delete', methods: ['POST'])]
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