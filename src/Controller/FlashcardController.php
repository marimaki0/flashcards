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

#[Route('/flashcard')]
final class FlashcardController extends AbstractController
{
    #[Route(name: 'app_flashcard_index', methods: ['GET'])]
    public function index(FlashcardRepository $flashcardRepository): Response
    {
        return $this->render('flashcard/index.html.twig', [
            'flashcards' => $flashcardRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_flashcard_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $flashcard = new Flashcard();
        $form = $this->createForm(FlashcardForm::class, $flashcard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
        return $this->render('flashcard/show.html.twig', [
            'flashcard' => $flashcard,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_flashcard_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Flashcard $flashcard, EntityManagerInterface $entityManager): Response
    {
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
        if ($this->isCsrfTokenValid('delete'.$flashcard->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($flashcard);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_flashcard_index', [], Response::HTTP_SEE_OTHER);
    }
}
