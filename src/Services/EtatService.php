<?php


namespace App\Services;


use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;

class EtatService
{

    public function miseAJourDesEtats(EtatRepository $etatRepository, SortieRepository $sortieRepository, EntityManagerInterface $entityManager):void{

        $sorties = $sortieRepository->listeDesSortie();

        foreach ($sorties as $sortieMaj){
            $sortie = $sortieRepository->find($sortieMaj);

            $finSortie = $sortie->getDateHeureDebut()+$sortie->getDuree();

            if($finSortie <= new \DateTime()){
                $sortie->setEtat($etatRepository->find(5));
                $sortieRepository->save($sortie, true);
            }
        }
    }
}