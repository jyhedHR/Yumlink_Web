<?php

namespace App\Entity;
use App\Repository\ProduitRepository;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 */
class Produit
{
    

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="Le nom ne peut pas être vide")
     * @Assert\Length(
     *      min=3,
     *      max=50,
     *      minMessage="Le nom doit contenir au moins {{ limit }} caractères",
     *      maxMessage="Le nom ne peut pas dépasser {{ limit }} caractères"
     * )
     */
    private $nom;
    
    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     * @Assert\NotBlank(message="Le prix ne peut pas être vide")
     * @Assert\PositiveOrZero(message="Le prix doit être un nombre positif ou zéro")
     */
    private $prix;
    
    /**
     * @var string
     *
     * @ORM\Column(name="diescription", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="La description ne peut pas être vide")
     * @Assert\Length(max=255, maxMessage="La description ne peut pas dépasser {{ limit }} caractères")
     * * @Assert\Expression(
     *     "not (value matches '/^[0-9\W].*$/')",
     *     message="La description ne peut pas commencer par un chiffre ou un symbole."
     * )
     */
    private $diescription;
    
    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
    
     */
    private $image;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDiescription(): ?string
    {
        return $this->diescription;
    }

    public function setDiescription(string $diescription): static
    {
        $this->diescription = $diescription;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }
    public function __toString()
    {
        return $this->nom; 
    }


}
