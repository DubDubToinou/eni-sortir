<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Repository\ParticipantRepository;
use JetBrains\PhpStorm\NoReturn;
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
        return $this->render('admin/gererUtilisateurNonActif.html.twig', [
            'utilisateursActif'=>$utilisateursActif
        ]);
    }

    #[Route('/supprimerUtilisateur/{id}', name: 'admin_supprimer_utilisateur')]
    public function supprimerUtilisateur(int $id, ParticipantRepository $participantRepository): Response
    {
        $participantASupprimer = $participantRepository->find($id);

        $participantRepository->remove($participantASupprimer, true);

        return $this->redirectToRoute('admin_gerer_utilisateur');
    }

    #[Route('/desactiverUtilisateur/{id}', name: 'admin_desactiver_utilisateur')]
    public function desactiverUtilisateur(int $id, ParticipantRepository $participantRepository): Response
    {
        $participantADesactiver = $participantRepository->find($id);
        $participantADesactiver->setActif(0);
        $participantRepository->save($participantADesactiver, true);


        return $this->redirectToRoute('admin_gerer_utilisateur');
    }

    #[Route('/activerUtilisateur/{id}', name: 'admin_activer_utilisateur')]
    public function activerUtilisateur(int $id, ParticipantRepository $participantRepository): Response
    {
        $participantAActiver = $participantRepository->find($id);
        $participantAActiver->setActif(1);
        $participantRepository->save($participantAActiver, true);


        return $this->redirectToRoute('admin_gerer_utilisateur_non_actif');
    }



}
