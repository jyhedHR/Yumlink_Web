<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation")
 * @ORM\Entity(repositoryClass=ReclamationRepository::class)
 */
class Reclamation
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="idr", type="integer", nullable=false)
     */
    private $idr;

    /**
     * @var string
     *
     * @ORM\Column(name="nomuser", type="string", length=55, nullable=false)
     * @Assert\NotBlank(message="Le nom d'utilisateur ne peut pas être vide")
     * @Assert\Length(
     *     max=55,
     *     maxMessage="Le nom d'utilisateur ne peut pas dépasser {{ limit }} caractères"
     * )
     */
    private $nomuser;

    /**
     * @var int
     *
     * @ORM\Column(name="iduser", type="integer", nullable=false)
     * @Assert\NotBlank(message="L'ID utilisateur ne peut pas être vide")
     */
    private $iduser;

    /**
     * @var string
     *
     * @ORM\Column(name="comentair", type="string", length=55, nullable=false)
     * @Assert\NotBlank(message="Le commentaire ne peut pas être vide")
     * @Assert\Length(
     *     max=55,
     *     maxMessage="Le commentaire ne peut pas dépasser {{ limit }} caractères"
     * )
     */
    private $comentair;

    // Getter and Setter methods for idr
    public function getIdr(): ?int
    {
        return $this->idr;
    }

    // Getter and Setter methods for nomuser
    public function getNomuser(): ?string
    {
        return $this->nomuser;
    }

    public function setNomuser(string $nomuser): self
    {
        $this->nomuser = $nomuser;
        return $this;
    }

    // Getter and Setter methods for iduser
    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function setIduser(int $iduser): self
    {
        $this->iduser = $iduser;
        return $this;
    }

    // Getter and Setter methods for comentair
    public function getComentair(): ?string
    {
        return $this->comentair;
    }

    public function setComentair(string $comentair): self
    {
        $this->comentair = $comentair;
        return $this;
    }
}
