<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/")]
class MainController extends AbstractController
{
    #[Route('', name: 'app_main')]
    public function index(): Response
    {

        //récupération de l'utilisateur en cours.
        $user = $this->getUser();

        return $this->render('main/index.html.twig', [
            'utilisateur' => $user,
        ]);
    }

    #[Route('/index', name: 'app_main_connecte')]
    public function indexConnecte(): Response
    {

        //récupération de l'utilisateur en cours.
        $user = $this->getUser();

        return $this->render('main/indexConnecte.html.twig', [
            'utilisateur' => $user,
        ]);
    }

}
