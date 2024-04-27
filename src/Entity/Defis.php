<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; 

/**
 * Defis
 *
 * @ORM\Table(name="defis", indexes={@ORM\Index(name="fk_id_u", columns={"idU"})})
 * @ORM\Entity
 */
class Defis
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_d", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idD;

    /**
     * @ORM\Column(name="nom_d", type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="ce champ ne peut pas être vide")
     */
    private $nomD;

    /**
     * @ORM\Column(name="photo_d", type="string", length=255 , )
     
     */
    private $photoD;

    /**
     * @ORM\Column(name="dis_d", type="string", length=50 , nullable=false)
     * @Assert\NotBlank(message="ce champ ne peut pas être vide")
     */
    private $disD;

    /**
     * @ORM\Column(name="delai", type="date" , nullable=false)
     * @Assert\NotBlank(message="ce champ ne peut pas être vide")
     */
    private $delai;

    /**
     * @ORM\Column(name="heure", type="time" , nullable=false)
     * @Assert\NotBlank(message="ce champ ne peut pas être vide")
     */
    private $heure;

   /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idU", referencedColumnName="idU")
     * })
     */
    private $idu;

    public function getIdD(): ?int
    {
        return $this->idD;
    }
   

    public function getNomD(): ?string
    {
        return $this->nomD;
    }

    public function setNomD(string $nomD): static
    {
        $this->nomD = $nomD;

        return $this;
    }

    public function getPhotoD(): ?string
    {
        return $this->photoD;
    }

    public function setPhotoD(string $photoD): static
    {
        $this->photoD = $photoD;

        return $this;
    }

    public function getDisD(): ?string
    {
        return $this->disD;
    }

    public function setDisD(string $disD): static
    {
        $this->disD = $disD;

        return $this;
    }

    public function getDelai(): ?\DateTimeInterface
    {
        return $this->delai;
    }

    public function setDelai(\DateTimeInterface $delai): static
    {
        $this->delai = $delai;

        return $this;
    }

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeInterface $heure): static
    {
        $this->heure = $heure;

        return $this;
    }

    public function getIdu(): ?User
    {
        return $this->idu;
    }

   
    public function setUser(?User $user): static
    {
        $this->user =$user;

        return $this;
    }


}
