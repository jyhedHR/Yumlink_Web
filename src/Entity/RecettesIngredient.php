<?php

namespace App\Entity;

use App\Repository\RecettesIngredientRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="recette_ingredient")
 */
class RecettesIngredient
{
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Recettes")
     * @ORM\JoinColumn(name="recette_id", referencedColumnName="id_r")
     */
    private $recette;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Ingredient")
     * @ORM\JoinColumn(name="ingredient_id", referencedColumnName="id_ing")
     */
    private $ingredient;
    /*
     * @ORM\Column(type="integer")
   
    private $quantite;  */

    public function getRecette(): ?Recettes
    {
        return $this->recette;
    }

    public function setRecette(?Recettes $recette): void
    {
        $this->recette = $recette;
    }

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(?Ingredient $ingredient): void
    {
        $this->ingredient = $ingredient;
    }

   /* public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): void
    {
        $this->quantite = $quantite;
    }*/

}
