<?php

namespace App\Controller\Api;

use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiVilleController extends AbstractController
{
    #[Route('/ville/{id}')]
    public function listeLieuParVille(int $idVille, VilleRepository $villeRepository, LieuRepository $lieuRepository): JsonResponse{

        $ville = $villeRepository->find($idVille);

        $lieu = $lieuRepository->find($ville);

        return $this->json($lieu);

    }

}