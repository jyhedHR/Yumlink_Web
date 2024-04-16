<?php

namespace App\Entity;
use App\Repository\VoteRepository;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vote
 *
 * @ORM\Table(name="vote", indexes={@ORM\Index(name="fk_part", columns={"idpart"}), @ORM\Index(name="fk_userr", columns={"idU"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\VoteRepository")
 */
class Vote
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_vote", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idVote;

    

     /**
     * @ORM\ManyToOne(targetEntity="Participant")
     * @ORM\JoinColumn(name="idpart", referencedColumnName="idpart")
     */
    private ?Participant $participant;
     /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="idU", referencedColumnName="idU")
     */
    private ?User $user;

    public function getIdVote(): ?int
    {
        return $this->idVote;
    }

    public function getParticipant(): ?Participant
    {
        return $this->participant;
    }

    public function setParticipant(?Participant $participant): static
    {
        $this->participant = $participant;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


}
