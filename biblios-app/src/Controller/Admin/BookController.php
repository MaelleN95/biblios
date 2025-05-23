<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Form\BookTypeForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/book')]
class BookController extends AbstractController
{
    #[Route('/', name: 'app_admin_book_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/book/index.html.twig', [
            'controller_name' => 'Admin/BookController',
        ]);
    }

     #[Route('/new', name: 'app_admin_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $book = new Book();

        $form = $this->createForm(BookTypeForm::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           // Fais quelque chose
        }

        return $this->render('admin/book/new.html.twig', [
            'form' => $form,
        ]);
    }
}
