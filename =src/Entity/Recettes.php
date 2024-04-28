<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recettes
 *
 * @ORM\Table(name="recettes", indexes={@ORM\Index(name="fk_recette_userr", columns={"idu"})})
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
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="chef", type="string", length=50, nullable=true)
     */
    private $chef;

    /**
     * @var array
     *
     * @ORM\Column(name="ingredients", type="json", nullable=false)
     */
    private $ingredients;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Categorie", type="string", length=50, nullable=true)
     */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
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
     *   @ORM\JoinColumn(name="idu", referencedColumnName="idU")
     * })
     */
    private $idu;


}
