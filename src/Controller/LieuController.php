<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lieu')]
class LieuController extends AbstractController
{
    #[Route('/', name: 'app_lieu_index', methods: ['GET'])]
    public function index(LieuRepository $lieuRepository): Response
    {
        return $this->render('lieu/index.html.twig', [
            'lieus' => $lieuRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_lieu_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LieuRepository $lieuRepository): Response
    {
        $lieu = new Lieu();
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lieuRepository->save($lieu, true);

            $this->addFlash('success', 'Le lieu a bien été ajouté');

            return $this->redirectToRoute('app_main_connecte', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lieu/new.html.twig', [
            'lieu' => $lieu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lieu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lieu $lieu, LieuRepository $lieuRepository): Response
    {
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lieuRepository->save($lieu, true);

            $this->addFlash('success', 'lieu modifié avec succes');

            return $this->redirectToRoute('app_lieu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lieu/edit.html.twig', [
            'lieu' => $lieu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lieu_delete', methods: ['POST' , 'GET'])]
    public function delete(int $id, Request $request, Lieu $lieu, LieuRepository $lieuRepository): Response
    {
        $lieuASupprimer = $lieuRepository->find($id);
        $lieuRepository->remove($lieuASupprimer, true);

        $this->addFlash('success', 'Le lieu a été supprimé');

        return $this->redirectToRoute('app_lieu_index', [], Response::HTTP_SEE_OTHER);
    }
}
