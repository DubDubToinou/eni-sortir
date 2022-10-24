<?php

namespace App\DTO;

Use Doctrine\ORM\Mapping as ORM;

class Rechercher
{

    public $mots;
    public $rechercheCampus;
    public $organisateur;
    public $inscrit;
    public $pasInscrit;
    public $dejaPasse;
    public $dateHeureDebut;
    public $dateLimiteInscription;

}