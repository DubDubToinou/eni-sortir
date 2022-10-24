<?php

namespace App\Controller;

use App\DTO\Rechercher;
use App\Entity\Participant;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
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
    public function indexConnecte(ParticipantRepository $participantRepository, CampusRepository $campusRepository, SortieRepository $sortieRepository): Response
    {

        $form = $this->createForm(Rechercher::class);

        $utilisateur = $this->getUser();
        $campus = $campusRepository->findAll();
        $sortie = $sortieRepository->findAll();

        return $this->render('main/indexConnecte.html.twig', [
            'utilisateur' =>$utilisateur,
            'campuses'=>$campus,
            'sorties'=>$sortie
        ]);
    }

}
