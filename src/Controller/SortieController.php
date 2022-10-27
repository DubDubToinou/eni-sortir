<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\AnnulationFormType;
use App\Form\LieuType;
use App\Form\SortieModifierType;
use App\Form\SortieType;
use App\Form\VilleType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Role\Role;


#[Route('/sortie')]
class SortieController extends AbstractController
{
    #[Route('/', name: 'app_sortie_index', methods: ['GET'])]
    public function index(SortieRepository $sortieRepository): Response
    {
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_sortie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SortieRepository $sortieRepository,ParticipantRepository $participantRepository, EtatRepository $etatRepository, LieuRepository $lieuRepository): Response
    {
       // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);
        $utilisateurConnecte = $participantRepository->find($this->getUser());

        $sortie->setCampus($utilisateurConnecte->getCampus());
        $sortie->setOrganisateur($utilisateurConnecte);
        $sortie->addParticipant($utilisateurConnecte);


        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('publier')->isClicked()){
                $sortie->setEtat($etatRepository->find(2));
                $sortieRepository->save($sortie, true);
            }else
                $sortie->setEtat($etatRepository->find(1));
                $sortieRepository->save($sortie, true);

                return $this->redirectToRoute('app_main_connecte', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
            'utilisateur'=>$utilisateurConnecte,
        ]);


        /*

        $form = $this->createForm(SortieModifierType::class, $sortie);
        $form->handleRequest($request);

        $lieu = new Lieu();
        $formLieu = $this->createForm(LieuType::class, $lieu);
        $formLieu->handleRequest($request);

        if($formLieu->isSubmitted() && $formLieu->isValid()){
            $lieuRepository->save($lieu,true);

            $this->addFlash('success', 'lieu ajouté avec success');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $sortieRepository->save($sortie, true);

            $this->addFlash('success', 'la sortie a été modifié.');
            return $this->redirectToRoute('app_main_connecte', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/edit.html.twig', [
            'formLieu'=>$formLieu,
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }
         */

    }

    #[Route('/{id}', name: 'app_sortie_show', methods: ['GET'])]
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sortie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sortie $sortie, SortieRepository $sortieRepository, LieuRepository $lieuRepository): Response
    {

        $form = $this->createForm(SortieModifierType::class, $sortie);
        $form->handleRequest($request);

        $lieu = new Lieu();
        $formLieu = $this->createForm(LieuType::class, $lieu);
        $formLieu->handleRequest($request);

        if($formLieu->isSubmitted() && $formLieu->isValid()){
            $lieuRepository->save($lieu,true);

            $this->addFlash('success', 'lieu ajouté avec success');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $sortieRepository->save($sortie, true);

            $this->addFlash('success', 'la sortie a été modifié.');
            return $this->redirectToRoute('app_main_connecte', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/edit.html.twig', [
            'formLieu'=>$formLieu,
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sortie_delete', methods: ['POST'])]
    public function delete(Request $request, Sortie $sortie, SortieRepository $sortieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sortie->getId(), $request->request->get('_token'))) {
            $sortieRepository->remove($sortie, true);
        }

        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/inscription/{id}', name: 'app_inscription_sortie', methods: ['GET', 'POST'])]
    public function inscriptionSortie(int $id, SortieRepository $sortieRepository, SortieRepository $sR, Sortie $inscrits, EtatRepository $etatRepository): Response
    {
        $participant = $this->getUser();
        $sortie = $sortieRepository->find($id);
        $inscrits = $sortie->getParticipants();

        foreach ($inscrits as $inscrit) {
            if ($participant === $inscrit) {
                $this->addFlash('failed', 'Vous êtes déjà inscrit');
                return $this->redirectToRoute('app_sortie_show', ['id' => $sortie->getId()], Response::HTTP_SEE_OTHER);
            }
        }

        if($sortie->getEtat()->getId() == 3){
            $this->addFlash('failed', 'La sortie est clôturé, désolé.');
            return $this->redirectToRoute('app_sortie_show', ['id' => $sortie->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->addFlash('success', 'Vous vous êtes inscrit');
        $sortie->addParticipant($participant);
        if ($sortie->getParticipants()->count() == $sortie->getNbInscriptionsMax()) {
            $sortie->setEtat($etatRepository->find(3));
        }else{
            $sortie->setEtat($etatRepository->find(2));
        }
        $sortieRepository->save($sortie, true);
        return $this->redirectToRoute('app_sortie_show', ['id' => $sortie->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/desistement/{id}', name: 'app_desistement_sortie', methods: ['GET', 'POST'])]
    public function desistementSortie(int $id, SortieRepository $sortieRepository, EtatRepository $etatRepository): Response
    {
        $participant = $this->getUser();
        $sortie = $sortieRepository->find($id);
        $sortie->removeParticipant($participant);
        if ($sortie->getParticipants()->count() < $sortie->getNbInscriptionsMax()){
            $sortie->setEtat($etatRepository->find(2));
        }

        $sortieRepository->save($sortie, true);
        $this->addFlash('success', 'Vous vous êtes désister.');

        return $this->redirectToRoute('app_sortie_show', ['id' => $sortie->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/annulation/{id}', name: 'app_sortie_annulation', methods: ['GET', 'POST'])]
    public function annulation(int $id, Request $request, Sortie $sortie, SortieRepository $sortieRepository, EtatRepository $etatRepository): Response
    {
        $dateDuJour = new \DateTime();
        $form = $this->createForm(AnnulationFormType::class, $sortie);
        $form->handleRequest($request);

        $participant = $this->getUser();
        if ($form->isSubmitted() && $form->isValid()) {

            if ($sortie->getDateHeureDebut()->getTimestamp() < $dateDuJour->getTimestamp()){
                $this->addFlash('failure', 'Événement déjà terminé. Annulation impossible.');
            }
        $sortie = $sortieRepository->find($id);
        $sortie->setEtat($etatRepository->find(6));
        $sortieRepository->save($sortie, true);
        $this->addFlash("succes", "Annulation confirmée.");
        return $this->redirectToRoute('app_sortie_show', ['id' => $sortie->getId()], Response::HTTP_SEE_OTHER);
    }

            return $this->renderForm('sortie/annulation.html.twig', [
                'sortie' => $sortie,
                'form' => $form,]);

    }


    #[Route('/publier/{id}', name: 'app_sortie_publier', methods: ['GET', 'POST'])]
    public function publier(int $id, Sortie $sortie, SortieRepository $sortieRepository, EtatRepository $etatRepository)
    {
        $participant = $this->getUser();
        $sortie = $sortieRepository->find($id);
        $sortie->setEtat($etatRepository->find(2));
        $sortieRepository->save($sortie, true);
        $this->addFlash('succes', 'Sortie Publiée et ouverte aux inscriptions.');
        return $this->redirectToRoute('app_main_connecte', [], Response::HTTP_SEE_OTHER);
    }
}
