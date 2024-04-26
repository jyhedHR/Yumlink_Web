<?php

namespace App\Entity;
use App\Repository\ParticipantRepository;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; 

/**
 * Participant
 *
 * @ORM\Table(name="participant", indexes={@ORM\Index(name="fk_id_user", columns={"idU"}), @ORM\Index(name="fk_id_d", columns={"id_d"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ParticipantRepository")
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
     * @ORM\ManyToOne(targetEntity="Defis")
     * @ORM\JoinColumn(name="id_d", referencedColumnName="id_d")
     */
    private ?Defis $defis;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="idU", referencedColumnName="idU")
     */
    private ?User $user;

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

    public function getDefis(): ?Defis
    {
        return $this->defis;
    }

    public function setIdD(?Defis $defis): static
    {
        $this->defis = $defis;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user =$user;

        return $this;
    }
   

    public function setDefis(?Defis $defis): static
    {
        $this->defis = $defis;

        return $this;
    }
   


}
