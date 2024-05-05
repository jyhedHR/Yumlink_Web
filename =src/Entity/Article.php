<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="article", indexes={@ORM\Index(name="fk_user_id", columns={"idU"})})
 * @ORM\Entity
 */
class Article
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_article", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idArticle;

    /**
     * @var string
     *
     * @ORM\Column(name="title_article", type="string", length=255, nullable=false)
     */
    private $titleArticle;

    /**
     * @var string
     *
     * @ORM\Column(name="img_article", type="string", length=255, nullable=false)
     */
    private $imgArticle;

    /**
     * @var string
     *
     * @ORM\Column(name="description_article", type="text", length=65535, nullable=false)
     */
    private $descriptionArticle;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_likes_article", type="integer", nullable=false)
     */
    private $nbLikesArticle;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_published", type="datetime", nullable=false)
     */
    private $datePublished;

    /**
     * @var array
     *
     * @ORM\Column(name="tags", type="json", nullable=false)
     */
    private $tags;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idU", referencedColumnName="idU")
     * })
     */
    private $idu;


}
