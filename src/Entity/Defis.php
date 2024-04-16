<?php

namespace App\Entity;
use App\Repository\DefisRepository;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; 

/**
 * @ORM\Table(name="defis", indexes={@ORM\Index(name="fk_id_u", columns={"idU"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\DefisRepository")
 */
class Defis
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="id_d", type="integer")
     */
    private int $idD;

    /**
     * @ORM\Column(name="nom_d", type="string", length=50)
     * @Assert\NotBlank
     */
    private string $nomD;

    /**
     * @ORM\Column(name="photo_d", type="string", length=255)
     * @Assert\NotBlank
     */
    private string $photoD;

    /**
     * @ORM\Column(name="dis_d", type="string", length=50)
     * @Assert\NotBlank
     */
    private string $disD;

    /**
     * @ORM\Column(name="delai", type="date")
     * @Assert\NotBlank
     */
    private \DateTimeInterface $delai;

    /**
     * @ORM\Column(name="heure", type="time")
     * @Assert\NotBlank
     */
    private \DateTimeInterface $heure;

   /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="idU", referencedColumnName="idU")
     */
    private ?User $user;

    public function getIdD(): int
    {
        return $this->idD;
    }
   

    public function getNomD(): string
    {
        return $this->nomD;
    }

    public function setNomD(string $nomD): self
    {
        $this->nomD = $nomD;

        return $this;
    }

    public function getPhotoD(): string
    {
        return $this->photoD;
    }

    public function setPhotoD(string $photoD): self
    {
        $this->photoD = $photoD;

        return $this;
    }

    public function getDisD(): string
    {
        return $this->disD;
    }

    public function setDisD(string $disD): self
    {
        $this->disD = $disD;

        return $this;
    }

    public function getDelai(): \DateTimeInterface
    {
        return $this->delai;
    }

    public function setDelai(\DateTimeInterface $delai): self
    {
        $this->delai = $delai;

        return $this;
    }

    public function getHeure(): \DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeInterface $heure): self
    {
        $this->heure = $heure;

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
}
