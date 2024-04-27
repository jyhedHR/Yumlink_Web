<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
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

    public function getIdArticle(): ?int
    {
        return $this->idArticle;
    }

    public function getTitleArticle(): ?string
    {
        return $this->titleArticle;
    }

    public function setTitleArticle(string $titleArticle): static
    {
        $this->titleArticle = $titleArticle;

        return $this;
    }

    public function getImgArticle(): ?string
    {
        return $this->imgArticle;
    }

    public function setImgArticle(string $imgArticle): static
    {
        $this->imgArticle = $imgArticle;

        return $this;
    }

    public function getDescriptionArticle(): ?string
    {
        return $this->descriptionArticle;
    }

    public function setDescriptionArticle(string $descriptionArticle): static
    {
        $this->descriptionArticle = $descriptionArticle;

        return $this;
    }

    public function getNbLikesArticle(): ?int
    {
        return $this->nbLikesArticle;
    }

    public function setNbLikesArticle(int $nbLikesArticle): static
    {
        $this->nbLikesArticle = $nbLikesArticle;

        return $this;
    }

    public function getDatePublished(): ?\DateTimeInterface
    {
        return $this->datePublished;
    }

    public function setDatePublished(\DateTimeInterface $datePublished): static
    {
        $this->datePublished = $datePublished;

        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): static
    {
        $this->tags = $tags;

        return $this;
    }

    public function getIdu(): ?User
    {
        return $this->idu;
    }

    public function setIdu(?User $idu): static
    {
        $this->idu = $idu;

        return $this;
    }


}
