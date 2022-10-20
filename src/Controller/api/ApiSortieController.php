<?php

namespace App\Controller\api;

use App\Entity\Campus;
use App\Repository\CampusRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/')]
class ApiSortieController extends AbstractController
{

    #[Route('/sortie/{idCampus}', name:'api_sortie_liste', methods: ['GET'])]
    public function liste(int $idCampus, SortieRepository $sortieRepository, CampusRepository $campusRepository)
    {

        $campus = $campusRepository->find($idCampus);
        $sorties = $sortieRepository->listeSortieParCampus($campus);
        return $this->json(
            $sorties,
            Response::HTTP_OK,
        );

    }
}