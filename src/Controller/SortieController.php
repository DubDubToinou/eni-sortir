<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


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
    public function new(Request $request, SortieRepository $sortieRepository, EtatRepository $etatRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        $sortie->setCampus($this->getUser()->getCampus());
        $sortie->setOrganisateur($this->getUser());
        $sortie->addParticipant($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('publish')->isClicked()) {
                $sortie->setEtat($etatRepository->find(2));
            } else {
                $sortie->setEtat($etatRepository->find(1));
            }

            $sortieRepository->save($sortie, true);

            return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sortie_show', methods: ['GET'])]
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sortie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sortie $sortie, SortieRepository $sortieRepository): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sortieRepository->save($sortie, true);

            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sortie_delete', methods: ['POST'])]
    public function delete(Request $request, Sortie $sortie, SortieRepository $sortieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->request->get('_token'))) {
            $sortieRepository->remove($sortie, true);
        }

        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/inscription/{id}', name:'app_inscription_sortie', methods: ['GET', 'POST'])]
    public function inscriptionSortie(int $id, SortieRepository $sortieRepository,SortieRepository $sR, Sortie $inscrits):Response
    {
        $participant = $this->getUser();
        $sortie = $sortieRepository->find($id);
        $inscrits = $sortie->getParticipants();
        foreach ( $inscrits as $inscrit){
            if ($participant === $inscrit) {
                $this->addFlash('failed', 'Vous êtes déjà inscrit');
                return $this->redirectToRoute('app_sortie_show',['id' => $sortie->getId()], Response::HTTP_SEE_OTHER);
                break;
            }
        }
        $this->addFlash('success', 'Vous vous êtes inscrit');
        $sortie->addParticipant($participant);
        $sortieRepository->save($sortie, true);
        return $this->redirectToRoute('app_sortie_show', ['id' => $sortie->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/desistement/{id}', name:'app_desistement_sortie', methods: ['GET', 'POST'])]
    public function desistementSortie(int $id, SortieRepository $sortieRepository, Sortie $sortie):Response
    {
        $participant = $this->getUser();
        $sortie=$sortieRepository->find($id);
        $sortie->removeParticipant($participant);
        $sortieRepository->save($sortie,true);
        $this->addFlash('success', 'Vous vous êtes désister.');

        return $this->redirectToRoute('app_sortie_show', ['id'=>$sortie->getId()], Response::HTTP_SEE_OTHER);
    }
}
