<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin_index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
        ]);
    }

    #[Route('/utilisateur', name: 'admin_gerer_utilisateur')]
    public function gererUtilisateur(): Response
    {
        return $this->render('admin/gererUtilisateur.html.twig', [
        ]);
    }



}
