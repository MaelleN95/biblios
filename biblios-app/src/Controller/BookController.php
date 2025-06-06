<?php

namespace App\Controller;

use App\Entity\Book;
use Pagerfanta\Pagerfanta;
use App\Repository\BookRepository;
use App\Repository\CommentRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_book_index', methods: ['GET'])]
    public function index(Request $request, BookRepository $repository): Response
    {
        $books = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($repository->createQueryBuilder('b')),
            $request->query->get('page', 1),
            20
        );

        return $this->render('book/index.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/{id}', name: 'app_book_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Book $book, CommentRepository $commentRepository): Response
    {

        if ($book === null) {
            throw $this->createNotFoundException('Book not found');
        }
        
        $comments = $commentRepository->findBy(['book' => $book], ['createdAt' => 'DESC']);

        $user = $this->getUser();

        if ($user !== null) {
            $userComment = $commentRepository->findOneBy([
                'user' => $user,
                'book' => $book,
            ]);
        }
        
        return $this->render('book/show.html.twig', [
            'book' => $book,
            'comments' => $comments,
            'userComment' => $userComment ?? null,
        ]);
    }
}
