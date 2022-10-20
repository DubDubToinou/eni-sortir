<?php

namespace App\Controller\api;

use App\Entity\Campus;
use App\Repository\CampusRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/')]
class ApiSortieController extends AbstractController
{

    #[Route('/sortie/{idCampus}')]
    public function liste(int $idCampus, SortieRepository $sortieRepository, CampusRepository $campusRepository)
    {

        $campus = $campusRepository->find($idCampus);

        $sortie = $sortieRepository;

    }

}