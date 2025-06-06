<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Pagerfanta\Pagerfanta;
use App\Form\RegistrationForm;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('admin/user')]
class UserController extends AbstractController
{
    #[Route('', name: 'app_admin_user_index', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        // Appel au constructeur de requête qui récup les infos de $userRepository
        $userqb = $userRepository->createQueryBuilder('user');

        // Appliquer les filtres et tris que l'on souhaite sur la requête
        $userqb->orderBy('user.firstname', 'ASC');

        $users = Pagerfanta::createForCurrentPageWithMaxPerPage(
            // fournir à Pagerfanta un QueryAdapter basé sur un QueryBuilder pour récupérer les données.
            new QueryAdapter($userqb),
            // Accéder au paramètres GET de l'url (équivalant de $_GET), et essaie de récup le paramètre "page" depuis l'URL. Le 2ème argument est pour la valeur par défaut
            $request->query->get('page', 1),
            // Définir le nombre maximal d’éléments affichés par page
            10
        );


        return $this->render('/admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_show',requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?User $user) : Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_user_edit',requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function new(?User $user, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $manager) : Response
    {

        if ($user) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $form = $this->createForm(RegistrationForm::class, $user, [
            'is_edit' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('app_admin_user_show', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
