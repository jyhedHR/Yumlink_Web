<?php

namespace App\Entity;
use App\Repository\IngredientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Ingredient
 *
 * @ORM\Table(name="ingredient")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\IngredientRepository")
 */
class Ingredient
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_ing", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idIng;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez remplir ce champ")
     * @ORM\Column(name="nom", type="string", length=20, nullable=false)
     */
    private $nom;


    public function getIdIng(): ?int
    {
        return $this->idIng;
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



}
