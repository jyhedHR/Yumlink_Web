<?php

namespace App\Entity;
use App\Repository\FavoriteRecipesRepository;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="favorite_recipes")
 * @ORM\Entity(repositoryClass="App\Repository\FavoriteRecipeRepository")
 */
class FavoriteRecipes
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="idU", nullable=false)
     */
    private $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Recettes")
     * @ORM\JoinColumn(name="recipe_id", referencedColumnName="id_r", nullable=false)
     */
    private ?Recettes $recipe;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;

        
    }

    public function getRecipe(): ?Recettes
    {
        return $this->recipe;
    }

    public function setRecipe(?Recettes $recipe): void
    {
        $this->recipe = $recipe;

       
    }
}
