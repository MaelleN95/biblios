<?php

namespace App\Controller\Admin;

use App\Entity\Editor;
use App\Form\EditorTypeForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/editor')]
class EditorController extends AbstractController
{
    #[Route('/', name: 'app_admin_editor_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/editor/index.html.twig', [
            'controller_name' => 'Admin/EditorController',
        ]);
    }

    #[Route('/new', name: 'app_admin_editor_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $editor = new Editor();
        $form = $this->createForm(EditorTypeForm::class, $editor);  
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // faire quelque chose
        }

         return $this->render('admin/editor/new.html.twig', [
            'form' => $form,
        ]);
    }   
}
