<?php

namespace App\Controller\Api;

use App\Repository\LieuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiVilleController extends AbstractController
{
    #[Route('/ville/{id}' , methods: ['POST', 'GET'])]
    public function listeLieuParVille(int $idVille, LieuRepository $lieuRepository): JsonResponse{


        $lieu = $lieuRepository->listeLieuParVille($idVille);
        dump($lieu);

        return $this->json(
            $lieu,
            Response::HTTP_OK
        );
    }

}