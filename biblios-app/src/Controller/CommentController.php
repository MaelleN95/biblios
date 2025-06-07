<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Enum\CommentStatus;
use App\Form\CommentTypeForm;
use App\Repository\BookRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Route('/book/{bookId}/comment', requirements: ['bookId' => '\d+'])]
final class CommentController extends AbstractController
{
    #[Route('/new', name: 'app_comment_new', methods: ['GET', 'POST'])]
    #[Route('/{id}/edit', name: 'app_comment_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function form(
        int $bookId,
        ?Comment $comment,
        Request $request,
        BookRepository $bookRepository,
        CommentRepository $commentRepository,
        EntityManagerInterface $manager
    ): Response
    {

        $book = $bookRepository->find($bookId);
        $connectedUser = $this->getUser();

        // Si pas trouvé de livre
        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        // Si un commentaire existe déjà
        if ($comment) {
            if ($comment->getUser() !== $connectedUser) {
                throw $this->createAccessDeniedException();
            }
        } else {
            if ($commentRepository->findBy(['user' => $connectedUser, 'book' => $book])) {
                throw $this->createAccessDeniedException('Comment already posted');
            }
            
            $comment ??= new Comment();
            $comment->setCreatedAt(new \DateTimeImmutable('Europe/Paris'));
            $comment->setUser($this->getUser());
            $comment->setBook($book);
        }

        $comment->setStatus(CommentStatus::Published->value);

        $form = $this->createForm(CommentTypeForm::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($comment->getId()) {
                $comment->setPublishedAt(new \DateTimeImmutable('Europe/Paris'));
            } else {
                $comment->setPublishedAt(null);
            }            

            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('app_book_show', ['id' => $bookId]);
        }

        return $this->render('comment/form.html.twig', [
            'form' => $form->createView(),
            'is_edit' => $comment->getId() !== null,
        ]);
    }
}
