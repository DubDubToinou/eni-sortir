<?php

namespace App\Controller;

use App\Form\RechercherType;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use App\Services\EtatService;
use Doctrine\ORM\EntityManagerInterface;
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
    public function indexConnecte(Request $request,EntityManagerInterface $entityManager, EtatService $etatService,EtatRepository $etatRepository, ParticipantRepository $participantRepository, CampusRepository $campusRepository, SortieRepository $sortieRepository): Response
    {

        //$etatService->miseAJourDesEtats($etatRepository, $sortieRepository,$entityManager);

        $campus = $campusRepository->findAll();
        $sortie = $sortieRepository->listeDesSortie();
        $utilisateur = $this->getUser();

        $formulaireRecherche = $this->createForm(RechercherType::class);
        $formulaireRecherche->handleRequest($request);

        if($formulaireRecherche->isSubmitted() && $formulaireRecherche->isValid()){
            $utilisateurConnecte = $participantRepository->find($this->getUser());

            $rechercheCampus = $formulaireRecherche->get('rechercheCampus')->getData();
            $mots = $formulaireRecherche->get('mots')->getData();
            $dateDebut = $formulaireRecherche->get('dateHeureDebut')->getData();
            $dateLimiteInscription = $formulaireRecherche->get('dateLimiteInscription')->getData();
            $organisateur = $formulaireRecherche->get('organisateur')->getData();
            $inscrit = $formulaireRecherche->get('inscrit')->getData();
            $pasInscrit = $formulaireRecherche->get('pasInscrit')->getData();
            $dejaPasse = $formulaireRecherche->get('dejaPasse')->getData();


            $sortie = $sortieRepository->rechercher($utilisateurConnecte->getId(), $mots, $rechercheCampus, $organisateur, $inscrit, $pasInscrit, $dejaPasse, $dateDebut, $dateLimiteInscription);

            return $this->renderForm('main/indexConnecte.html.twig', [
                'utilisateur' =>$utilisateurConnecte,
                'campuses'=>$campus,
                'sorties'=>$sortie,
                'form'=>$formulaireRecherche,
            ]);
        }

        return $this->renderForm('main/indexConnecte.html.twig', [
            'utilisateur' =>$utilisateur,
            'campuses'=>$campus,
            'sorties'=>$sortie,
            'form'=>$formulaireRecherche,
        ]);
    }

}
