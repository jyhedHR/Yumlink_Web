<?php

namespace App\Entity;
use App\Repository\FavoriteRecipesRepository;

use Doctrine\ORM\Mapping as ORM;

/**
 * FavoriteRecipes
 *
 * @ORM\Table(name="favorite_recipes", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="recipe_id", columns={"recipe_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\FavoriteRecipeRepository")
 */
class FavoriteRecipes
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
     * @var int|null
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="recipe_id", type="integer", nullable=true)
     */
    private $recipeId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getRecipeId(): ?int
    {
        return $this->recipeId;
    }

    public function setRecipeId(?int $recipeId): static
    {
        $this->recipeId = $recipeId;

        return $this;
    }


}
