<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
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
    public function indexUtilisateurActif(ParticipantRepository $participantRepository): Response
    {
        $utilisateursActif = $participantRepository->afficherTousLesUtilisateursActifs();
        dump($utilisateursActif);
        return $this->render('admin/gererUtilisateur.html.twig', [
            'utilisateursActif'=>$utilisateursActif
        ]);
    }

    #[Route('/utilisateurNonActif', name: 'admin_gerer_utilisateur_non_actif')]
    public function indexUtilisateurNonActif(ParticipantRepository $participantRepository): Response
    {
        $utilisateursActif = $participantRepository->afficherTousLesutilisateursInactifs();
        dump($utilisateursActif);
        return $this->render('admin/gererUtilisateurNonActif.html.twig', [
            'utilisateursActif'=>$utilisateursActif
        ]);
    }



}
