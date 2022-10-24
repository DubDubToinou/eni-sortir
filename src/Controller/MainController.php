<?php

namespace App\Controller;

use App\DTO\Rechercher;
use App\Entity\Participant;
use App\Form\RechercherType;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function indexConnecte(Request $request, ParticipantRepository $participantRepository, CampusRepository $campusRepository, SortieRepository $sortieRepository): Response
    {

        $campus = $campusRepository->findAll();
        $sortie = $sortieRepository->findAll();
        $formulaireRecherche = $this->createForm(RechercherType::class);
        $utilisateur = $this->getUser();
        $formulaireRecherche->handleRequest($request);

        if($formulaireRecherche->isSubmitted() && $formulaireRecherche->isValid()){
            $utilisateur = $participantRepository->find($this->getUser());

            $mots = $formulaireRecherche->get('mots')->getData();
            $rechercheCampus = $formulaireRecherche->get('rechercheCampus')->getData();
            $organisateur = $formulaireRecherche->get('organisateur')->getData();
            $inscrit = $formulaireRecherche->get('inscrit')->getData();
            $pasInscrit = $formulaireRecherche->get('pasInscrit')->getData();
            $dejaPasse = $formulaireRecherche->get('dejaPasse')->getData();
            $dateDebut = $formulaireRecherche->get('dateHeureDebut')->getData();
            $dateFin = $formulaireRecherche->get('dateLimiteInscription')->getData();

            $sortie = $sortieRepository->rechercher($utilisateur->getId(), $mots, $rechercheCampus, $organisateur, $inscrit, $pasInscrit, $dejaPasse, $dateDebut,$dateFin);

            return $this->renderForm('main/indexConnecte.html.twig', [
                'utilisateur' =>$utilisateur,
                'campuses'=>$campus,
                'sorties'=>$sortie,
                'form'=>$formulaireRecherche,
            ]);
        }


        $campus = $campusRepository->findAll();

        return $this->render('main/indexConnecte.html.twig', [
            'utilisateur' =>$utilisateur,
            'campuses'=>$campus,
            'sorties'=>$sortie,
            'form'=>$formulaireRecherche,
        ]);
    }

}
