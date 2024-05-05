<?php

namespace App\Entity;

use App\Repository\ReprandreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reprandre
 *
 * @ORM\Table(name="reprandre")
 * @ORM\Entity(repositoryClass=ReprandreRepository::class)
 */
class Reprandre
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     * @Assert\NotBlank(message="La description ne peut pas être vide")
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="id_client", type="integer", nullable=false)
     * @Assert\NotBlank(message="L'ID du client ne peut pas être vide")
     */
    private $idClient;

  

    // Getter and Setter methods for id
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter and Setter methods for description
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    // Getter and Setter methods for idClient
    public function getIdClient(): ?int
    {
        return $this->idClient;
    }

    public function setIdClient(int $idClient): self
    {
        $this->idClient = $idClient;
        return $this;
    }

   
}
