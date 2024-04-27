<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire", indexes={@ORM\Index(name="fk_com_user_id", columns={"idU"}), @ORM\Index(name="fk_article_id", columns={"id_article"})})
 * @ORM\Entity
 */
class Commentaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="comment_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $commentId;

    /**
     * @var int
     *
     * @ORM\Column(name="id_article", type="integer", nullable=false)
     */
    private $idArticle;

    /**
     * @var string
     *
     * @ORM\Column(name="comment_text", type="text", length=65535, nullable=false)
     * @Assert\NotBlank(message="Comment cannot be empty")     
     */
    private $commentText;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="comment_date", type="date", nullable=false)
     */
    private $commentDate;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="idU", referencedColumnName="idU")
     */
    private ?User $user;

    public function getCommentId(): ?int
    {
        return $this->commentId;
    }

    public function getCommentText(): ?string
    {
        return $this->commentText;
    }

    public function setCommentText(string $commentText): static
    {
        $this->commentText = $commentText;

        return $this;
    }

    public function getCommentDate(): ?\DateTimeInterface
    {
        return $this->commentDate;
    }

    public function setCommentDate(\DateTimeInterface $commentDate): static
    {
        $this->commentDate = $commentDate;

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

    public function getIdArticle(): ?int
    {
        return $this->idArticle;
    }

    public function setIdArticle(int $idArticle): static
    {
        $this->idArticle = $idArticle;

        return $this;
    }
}
