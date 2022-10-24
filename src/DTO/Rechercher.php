<?php

namespace App\DTO;

Use Doctrine\ORM\Mapping as ORM;

class Rechercher
{

    public $rechercheCampus;
    public $mots;
    public $organisateur;
    public $inscrit;
    public $pasInscrit;
    public $dejaPasse;
    public $dateHeureDebut;
    public $dateLimiteInscription;

    /**
     * @return mixed
     */
    public function getDateHeureDebut()
    {
        return $this->dateHeureDebut;
    }

    /**
     * @param mixed $dateHeureDebut
     */
    public function setDateHeureDebut($dateHeureDebut): void
    {
        $this->dateHeureDebut = $dateHeureDebut;
    }

    /**
     * @return mixed
     */
    public function getRechercheCampus()
    {
        return $this->rechercheCampus;
    }

    /**
     * @param mixed $rechercheCampus
     */
    public function setRechercheCampus($rechercheCampus): void
    {
        $this->rechercheCampus = $rechercheCampus;
    }

    /**
     * @return mixed
     */
    public function getMots()
    {
        return $this->mots;
    }

    /**
     * @param mixed $mots
     */
    public function setMots($mots): void
    {
        $this->mots = $mots;
    }

    /**
     * @return mixed
     */
    public function getOrganisateur()
    {
        return $this->organisateur;
    }

    /**
     * @param mixed $organisateur
     */
    public function setOrganisateur($organisateur): void
    {
        $this->organisateur = $organisateur;
    }

    /**
     * @return mixed
     */
    public function getInscrit()
    {
        return $this->inscrit;
    }

    /**
     * @param mixed $inscrit
     */
    public function setInscrit($inscrit): void
    {
        $this->inscrit = $inscrit;
    }

    /**
     * @return mixed
     */
    public function getPasInscrit()
    {
        return $this->pasInscrit;
    }

    /**
     * @param mixed $pasInscrit
     */
    public function setPasInscrit($pasInscrit): void
    {
        $this->pasInscrit = $pasInscrit;
    }

    /**
     * @return mixed
     */
    public function getDejaPasse()
    {
        return $this->dejaPasse;
    }

    /**
     * @param mixed $dejaPasse
     */
    public function setDejaPasse($dejaPasse): void
    {
        $this->dejaPasse = $dejaPasse;
    }

    /**
     * @return mixed
     */
    public function getDateLimiteInscription()
    {
        return $this->dateLimiteInscription;
    }

    /**
     * @param mixed $dateLimiteInscription
     */
    public function setDateLimiteInscription($dateLimiteInscription): void
    {
        $this->dateLimiteInscription = $dateLimiteInscription;
    }





}