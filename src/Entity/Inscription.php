<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InscriptionRepository::class)
 */
class Inscription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_inscription;

    /**
     * @ORM\ManyToOne  (targetEntity=Participant::class, inversedBy="inscription", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $no_participant;

    /**
     * @ORM\ManyToOne (targetEntity=Sortie::class, inversedBy="inscription", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $no_sortie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->date_inscription;
    }

    public function setDateInscription(\DateTimeInterface $date_inscription): self
    {
        $this->date_inscription = $date_inscription;

        return $this;
    }

    public function getNoParticipant(): ?Participant
    {
        return $this->no_participant;
    }

    public function setNoParticipant(Participant $no_participant): self
    {
        $this->no_participant = $no_participant;

        return $this;
    }

    public function getNoSortie(): ?Sortie
    {
        return $this->no_sortie;
    }

    public function setNoSortie(Sortie $no_sortie): self
    {
        $this->no_sortie = $no_sortie;

        return $this;
    }
}
