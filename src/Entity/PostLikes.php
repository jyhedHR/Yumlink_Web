<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PostLikes
 *
 * @ORM\Table(name="post_likes", uniqueConstraints={@ORM\UniqueConstraint(name="unique_like", columns={"id_article", "idU"})}, indexes={@ORM\Index(name="idU", columns={"idU"}), @ORM\Index(name="IDX_DED1C292DCA7A716", columns={"id_article"})})
 * @ORM\Entity(repositoryClass="App\Repository\PostLikesRepository")
 */
class PostLikes
{
    /**
     * @var int
     *
     * @ORM\Column(name="like_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $likeId;

    /**
     * @ORM\ManyToOne(targetEntity="Article")
     * @ORM\JoinColumn(name="id_article", referencedColumnName="id_article")
     */
    private ?Article $article;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="idU", referencedColumnName="idU")
     */
    private ?User $user;

    

    public function getLikeId(): ?int
    {
        return $this->likeId;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }


}
