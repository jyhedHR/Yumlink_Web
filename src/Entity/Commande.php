<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_com", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCom;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="id_client", type="integer", nullable=false)
     */
    private $idClient;

    public function getIdCom(): ?int
    {
        return $this->idCom;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIdClient(): ?int
    {
        return $this->idClient;
    }

    public function setIdClient(int $idClient): static
    {
        $this->idClient = $idClient;

        return $this;
    }


}
