<?php

namespace App\Entity;
use App\Repository\RecettesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Recettes
 *
 * @ORM\Table(name="recettes", indexes={@ORM\Index(name="fk_recette_users", columns={"iduser"}), @ORM\Index(name="fk_recettes_user", columns={"idu"})})
 * @ORM\Entity
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
     *
     * @ORM\Column(name="nom", type="string", length=20, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=100, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="imgSrc", type="string", length=255, nullable=false)
     */
    private $imgsrc;

    /**
     * @var int
     *
     * @ORM\Column(name="calorie", type="integer", nullable=false)
     */
    private $calorie;

    /**
     * @var int
     *
     * @ORM\Column(name="protein", type="integer", nullable=false)
     */
    private $protein;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iduser", referencedColumnName="idU")
     * })
     */
    private ?User $user;
    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
    }

    public function getIngredients(): ArrayCollection
    {
        return $this->ingredients;
    }

    public function setIngredients(ArrayCollection $ingredients): void
    {
        $this->ingredients = $ingredients;
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

   /* public function getIngredients(): array
    {
        return $this->ingredients;
    }

    public function setIngredients(array $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }*/

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

    

    public function setIduser(?User $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }

 

    public function setIdu(?User $idu): static
    {
        $this->idu = $idu;

        return $this;
    }


}
