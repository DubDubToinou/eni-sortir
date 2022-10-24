<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function save(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function listeSortieParCampus(Campus $campus)
    {
        $qb = $this->createQueryBuilder('s');
        $qb->innerJoin('s.campus', 'campus')
            ->addSelect('campus');

        $qb->where('s.campus = :campus')
            ->setParameter('campus', $campus);

        return $qb->getQuery()->getResult();
    }

    //QUERY BUILDER pour affichage d'une sortie avec les participants.

    public function listeSortieAvecParticipant($id)
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s', 'p')
            ->join('s.participants', 'p')
            ->andWhere('s.id = :id')
            ->setParameter(':id', $id);

        return $qb->getQuery()->getResult();

    }


    public function listeDesSortie(){
        $qb = $this->createQueryBuilder('s')
            ->select('s','p','e')
            ->leftJoin('s.participants', 'p')
            ->leftJoin('s.etat', 'e');


            return $qb->getQuery()->getResult();
    }


    public function rechercher($id, $mots = null, $rechercheCampus = null, $organisateur = false,
                               $inscrit = false, $pasInscrit = false, $dejaPasse = false,
                               $dateHeureDebut = null, $dateLimiteInscription = null
    )
    {
        $query = $this->createQueryBuilder('s');

        if ($mots != null) {
            $query->where('MATCH_AGAINST(s.nom, s.infosSortie) AGAINST (:mots boolean)>0')->setParameter('mots', $mots);
        }

        if ($rechercheCampus != null) {
            $query->leftJoin('s.campus', 'c');
            $query->andWhere('c.id = :id')->setParameter('id', $rechercheCampus);
        }

        if ($organisateur) {
            $query->andWhere('s.organisateur = :id')->setParameter('id', $id);
        }


        if ($inscrit) {
            $query->innerJoin('s.participants', 'p');
            $query->andWhere('p.id = :id')->setParameter('id', $id);
        }

        if ($pasInscrit) {
            $query->join('s.participants', 'p');
            $query->andWhere('p.id != :id')->setParameter('id', $id);
        }


        if ($dejaPasse) {
            $query->andWhere('s.etat = 5 ');
        }

        if ($dateLimiteInscription != null) {
            $query->andWhere('s.dateHeureDebut < :dateHeureDebut')
                ->setParameter('dateHeureDebut', $dateLimiteInscription);
            $query->orderBy('s.dateHeureDebut', 'ASC');
        }

        if ($dateHeureDebut != null) {
            $query->andWhere('s.dateLimiteInscription > :dateLimiteInscription')
                ->setParameter('dateLimiteInscription', $dateHeureDebut);
            $query->orderBy('s.dateHeureDebut', 'ASC');
        }

        return $query->getQuery()->getResult();
    }


//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
