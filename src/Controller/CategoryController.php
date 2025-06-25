<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryForm;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for managing categories
 */
#[Route('/category')]
final class CategoryController extends AbstractController
{
    /**
     * Lists all categories
     * @param CategoryRepository $categoryRepository Repository to retrieve Category entities
     * @return Response Renders the index view with all categories
     */
    #[Route('', name: 'app_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'selectedCategoryName' => 'All Categories',
            'selectedCategory' => 'all',
            'flashcards' => [], // Dodana pusta tablica fiszek żeby szablon nie crashował
        ]);
    }

    /**
     * Creates a new category
     * @param Request $request HTTP request containing form data
     * @param EntityManagerInterface $entityManager Entity manager for persisting data
     * @return Response Renders the new form or redirects after successful creation
     */
    #[Route('/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryForm::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    /**
     * Shows details of a specific category
     * @param Category $category The category entity to display
     * @return Response Renders the show view with category details
     */
    #[Route('/show/{id}', name: 'app_category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * Edits an existing category
     * @param Request $request HTTP request containing form data
     * @param Category $category The category entity to edit
     * @param EntityManagerInterface $entityManager Entity manager for persisting changes
     * @return Response Renders the edit form or redirects after successful update
     */
    #[Route('/edit/{id}', name: 'app_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryForm::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    /**
     * Deletes a category
     * @param Request $request HTTP request containing CSRF token
     * @param Category $category The category entity to delete
     * @param EntityManagerInterface $entityManager Entity manager for removing data
     * @return Response Redirects to the category index after deletion
     */
    #[Route('/delete/{id}', name: 'app_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }
}