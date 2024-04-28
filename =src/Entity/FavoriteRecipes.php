<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FavoriteRecipes
 *
 * @ORM\Table(name="favorite_recipes", indexes={@ORM\Index(name="recipe_id", columns={"recipe_id"}), @ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
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


}
