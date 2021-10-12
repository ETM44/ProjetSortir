<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtatRepository::class)
 */
class Etat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="etat")
     */
    private $etatSorties;

    public function __construct()
    {
        $this->etatSorties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getEtatSorties(): Collection
    {
        return $this->etatSorties;
    }

    public function addEtatSorty(Sortie $etatSorty): self
    {
        if (!$this->etatSorties->contains($etatSorty)) {
            $this->etatSorties[] = $etatSorty;
            $etatSorty->setEtat($this);
        }

        return $this;
    }

    public function removeEtatSorty(Sortie $etatSorty): self
    {
        if ($this->etatSorties->removeElement($etatSorty)) {
            // set the owning side to null (unless already changed)
            if ($etatSorty->getEtat() === $this) {
                $etatSorty->setEtat(null);
            }
        }

        return $this;
    }
}
