<?php

namespace App\Entity;
use App\Repository\RecettesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Recettes
 *
 * @ORM\Table(name="recettes", indexes={@ORM\Index(name="fk_recette_userr", columns={"idu"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\RecettesRepository")
 */
class Recettes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_r", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idR;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez remplir ce champ")
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string|null
     * @Assert\NotBlank(message="Veuillez remplir ce champ")
     * @ORM\Column(name="chef", type="string", length=50, nullable=true)
     */
    private $chef;


    /**
     * @var string|null
     * @Assert\NotBlank(message="Veuillez remplir ce champ")
     * @ORM\Column(name="Categorie", type="string", length=50, nullable=true)
     */
    private $categorie;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez remplir ce champ")
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(name="imgSrc", type="string", length=255, nullable=false)
     */
    private $imgsrc;

    /**
     * @var int
     * @Assert\NotBlank
     * @Assert\Type(type="numeric")
     * @ORM\Column(name="calorie", type="integer", nullable=false)
     */
    private $calorie;

    /**
     * @var int
     * @Assert\NotBlank
     * @Assert\Type(type="numeric")
     * @ORM\Column(name="protein", type="integer", nullable=false)
     */
    private $protein;
      /**
     * @ORM\Column(name="rating",type="integer")
     */
    private $rating;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="idU", referencedColumnName="idU")
     */
    private ?User $user;
   
    private Ingredient $ingredient ; 

    public function getIngredient() : ?ingredient 
    {
        return $this->ingredient ; 
    }

    public function getIdR(): ?int
    {
        return $this->idR;
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

    public function getChef(): ?string
    {
        return $this->chef;
    }

    public function setChef(?string $chef): static
    {
        $this->chef = $chef;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImgsrc(): ?string
    {
        return $this->imgsrc;
    }

    public function setImgsrc(string $imgsrc): static
    {
        $this->imgsrc = $imgsrc;

        return $this;
    }

    public function getCalorie(): ?int
    {
        return $this->calorie;
    }

    public function setCalorie(int $calorie): static
    {
        $this->calorie = $calorie;

        return $this;
    }

    public function getProtein(): ?int
    {
        return $this->protein;
    }

    public function setProtein(int $protein): static
    {
        $this->protein = $protein;

        return $this;
    }
    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

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