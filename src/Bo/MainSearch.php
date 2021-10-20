<?php

namespace App\Bo;

class MainSearch
{
    private $site;

    private $nom;

    private $dateHeureDebut;

    private $dateHeureFin;

    private $sortieOrganisateur;

    private $sortieInscrit;

    private $sortiePasInscrit;

    private $sortiePassees;

    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     */
    public function setSite($site): void
    {
        $this->site = $site;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

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
    public function getDateHeureFin()
    {
        return $this->dateHeureFin;
    }

    /**
     * @param mixed $dateHeureFin
     */
    public function setDateHeureFin($dateHeureFin): void
    {
        $this->dateHeureFin = $dateHeureFin;
    }

    /**
     * @return mixed
     */
    public function getSortieOrganisateur()
    {
        return $this->sortieOrganisateur;
    }

    /**
     * @param mixed $sortieOrganisateur
     */
    public function setSortieOrganisateur($sortieOrganisateur): void
    {
        $this->sortieOrganisateur = $sortieOrganisateur;
    }

    /**
     * @return mixed
     */
    public function getSortieInscrit()
    {
        return $this->sortieInscrit;
    }

    /**
     * @param mixed $sortieInscrit
     */
    public function setSortieInscrit($sortieInscrit): void
    {
        $this->sortieInscrit = $sortieInscrit;
    }

    /**
     * @return mixed
     */
    public function getSortiePasInscrit()
    {
        return $this->sortiePasInscrit;
    }

    /**
     * @param mixed $sortiePasInscrit
     */
    public function setSortiePasInscrit($sortiePasInscrit): void
    {
        $this->sortiePasInscrit = $sortiePasInscrit;
    }

    /**
     * @return mixed
     */
    public function getSortiePassees()
    {
        return $this->sortiePassees;
    }

    /**
     * @param mixed $sortiePassees
     */
    public function setSortiePassees($sortiePassees): void
    {
        $this->sortiePassees = $sortiePassees;
    }

}
