<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusType;
use App\Repository\CampusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/campus')]
class CampusController extends AbstractController
{
    #[Route('/', name: 'app_campus_index', methods: ['GET'])]
    public function index(CampusRepository $campusRepository): Response
    {
        $campuses = $campusRepository->findAll();

        return $this->render('campus/index.html.twig', [
            'campuses'=>$campuses
        ]);
    }

    #[Route('/new', name: 'app_campus_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CampusRepository $campusRepository): Response
    {
        $campus = new Campus();
        $form = $this->createForm(CampusType::class, $campus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $campusRepository->save($campus, true);

            $this->addFlash('success', 'Campus crée avec succes');

            return $this->redirectToRoute('app_campus_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('campus/new.html.twig', [
            'campus' => $campus,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_campus_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Campus $campus, CampusRepository $campusRepository): Response
    {
        $form = $this->createForm(CampusType::class, $campus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $campusRepository->save($campus, true);

            $this->addFlash('success', 'Campus modifié avec succes');

            return $this->redirectToRoute('app_campus_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('campus/edit.html.twig', [
            'campus' => $campus,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_campus_delete', methods: ['POST', 'GET'])]
    public function delete(int $id, Request $request, Campus $campus, CampusRepository $campusRepository): Response
    {
        $campusASupprimer = $campusRepository->find($id);
        $campusRepository->remove($campusASupprimer, true);

        $this->addFlash('success', 'Campus supprimé avec succes');

        return $this->redirectToRoute('app_campus_index', [], Response::HTTP_SEE_OTHER);
    }
}
