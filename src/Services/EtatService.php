<?php


namespace App\Services;


use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class EtatService
{

    public function miseAJourDesEtats(EtatRepository $etatRepository, SortieRepository $sortieRepository, EntityManagerInterface $entityManager): void
    {

        $sorties = $sortieRepository->listeDesSortie();
        $dateDuJour = new \DateTime();
        $dateArchive = new \DateTime('-1 month');

        /*
         * ETAT
         *
         *      1 - Crée
         *      2 - Ouverte
         *      3 - Cloturée
         * 4 - Activité en cours
         * 5 - Passée
         *          6 - Annulé
         * 7 - Archivé
         */


        foreach ($sorties as $sortieMaj) {
            $sortie = $sortieRepository->find($sortieMaj);

            $dateFinSortie = $sortie->getDateHeureDebut()->getTimestamp() + $sortie->getDuree()->getTimestamp();


            if ($dateFinSortie <= $dateArchive->getTimestamp()) {
                $sortie->setEtat($etatRepository->find(7));
                $entityManager->persist($sortie);

            } elseif ($dateFinSortie <= $dateDuJour->getTimestamp()) {

                $sortie->setEtat($etatRepository->find(5));
                $entityManager->persist($sortie);

            } elseif ($dateDuJour->getTimestamp() > $sortie->getDateHeureDebut()->getTimestamp() && $dateDuJour->getTimestamp() < $dateFinSortie) {
                $sortie->setEtat($etatRepository->find(4));
                $entityManager->persist($sortie);
            }
            $entityManager->flush();
        }

    }
}