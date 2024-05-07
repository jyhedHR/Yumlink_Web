<?php

namespace App\Entity;
use App\Repository\PanierRepository;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="panier", indexes={@ORM\Index(name="fk_idproduit", columns={"id_produit"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PanierRepository")
 */
class Panier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="idp", type="integer")
     */
    private int $idp;

    /**
     * @ORM\Column(name="quantite", type="integer")
     */
    private int $quantite;

    /**
     * @ORM\Column(name="prixtotal", type="float")
     */
    private float $prixtotal;

    /**
     * @ORM\Column(name="id_client", type="integer")
     */
    private int $idClient;

    /**
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumn(name="id_produit", referencedColumnName="id")
     */
    private ?Produit $produit;

    public function getIdp(): int
    {
        return $this->idp;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrixtotal(): float
    {
        return $this->prixtotal;
    }

    public function setPrixtotal(float $prixtotal): self
    {
        $this->prixtotal = $prixtotal;

        return $this;
    }

    public function getIdClient(): int
    {
        return $this->idClient;
    }

    public function setIdClient(int $idClient): self
    {
        $this->idClient = $idClient;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }
}
