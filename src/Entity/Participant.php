<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participant
 *
 * @ORM\Table(name="participant", indexes={@ORM\Index(name="fk_id_user", columns={"idU"}), @ORM\Index(name="fk_id_d", columns={"id_d"})})
 * @ORM\Entity
 */
class Participant
{
    /**
     * @var int
     *
     * @ORM\Column(name="idpart", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idpart;

    /**
     * @var string
     *
     * @ORM\Column(name="photo_p", type="string", length=255, nullable=false)
     */
    private $photoP;

    /**
     * @var int|null
     *
     * @ORM\Column(name="vote", type="integer", nullable=true)
     */
    private $vote;

    /**
     * @var \Defis
     *
     * @ORM\ManyToOne(targetEntity="Defis")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_d", referencedColumnName="id_d")
     * })
     */
    private $idD;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idU", referencedColumnName="idU")
     * })
     */
    private $idu;

    public function getIdpart(): ?int
    {
        return $this->idpart;
    }

    public function getPhotoP(): ?string
    {
        return $this->photoP;
    }

    public function setPhotoP(string $photoP): static
    {
        $this->photoP = $photoP;

        return $this;
    }

    public function getVote(): ?int
    {
        return $this->vote;
    }

    public function setVote(?int $vote): static
    {
        $this->vote = $vote;

        return $this;
    }

    public function getIdD(): ?Defis
    {
        return $this->idD;
    }

    public function setIdD(?Defis $idD): static
    {
        $this->idD = $idD;

        return $this;
    }

    public function getIdu(): ?User
    {
        return $this->idu;
    }

    public function setIdu(?User $idu): static
    {
        $this->idu = $idu;

        return $this;
    }


}
