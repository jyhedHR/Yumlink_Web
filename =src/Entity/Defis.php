<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Defis
 *
 * @ORM\Table(name="defis", indexes={@ORM\Index(name="fk_id_u", columns={"idU"})})
 * @ORM\Entity
 */
class Defis
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_d", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idD;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_d", type="string", length=50, nullable=false)
     */
    private $nomD;

    /**
     * @var string
     *
     * @ORM\Column(name="photo_d", type="string", length=255, nullable=false)
     */
    private $photoD;

    /**
     * @var string
     *
     * @ORM\Column(name="dis_d", type="string", length=50, nullable=false)
     */
    private $disD;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delai", type="date", nullable=false)
     */
    private $delai;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heure", type="time", nullable=false)
     */
    private $heure;

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
