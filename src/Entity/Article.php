<?php

namespace App\Entity;
use App\Repository\ArticleRepository;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Article
 *
 * @ORM\Table(name="article", indexes={@ORM\Index(name="fk_user_id", columns={"idU"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
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
     * @Assert\NotBlank(message="Title is required")
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
     * @Assert\NotBlank(message="Description is required")
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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="idU", referencedColumnName="idU")
     */
    private ?User $user;
    public function getIdArticle(): ?int
    {
        return $this->idArticle;
    }

    public function getTitleArticle(): ?string
    {
        return $this->titleArticle;
    }

    public function setTitleArticle(?string $titleArticle): self
    {
        $this->titleArticle = $titleArticle;

        return $this;
    }

    public function getImgArticle(): ?string
    {
        return $this->imgArticle;
    }

    public function setImgArticle(string $imgArticle): self
    {
        $this->imgArticle = $imgArticle;

        return $this;
    }

    public function getDescriptionArticle(): ?string
    {
        return $this->descriptionArticle;
    }

    public function setDescriptionArticle(?string $descriptionArticle): self
    {
        $this->descriptionArticle = $descriptionArticle;

        return $this;
    }

    public function getNbLikesArticle(): ?int
    {
        return $this->nbLikesArticle;
    }

    public function setNbLikesArticle(int $nbLikesArticle): self
    {
        $this->nbLikesArticle = $nbLikesArticle;

        return $this;
    }

    public function getDatePublished(): ?\DateTimeInterface
    {
        return $this->datePublished;
    }

    public function setDatePublished(\DateTimeInterface $datePublished): self
    {
        $this->datePublished = $datePublished;

        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): self
    {
        $this->tags = $tags;

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
    
    public function incrementLikes(): void
    {
        $this->nbLikesArticle++;
    }

    public function decrementLikes(): void
    {
        if ($this->nbLikesArticle > 0) {
            $this->nbLikesArticle--;
        }
    }

}
