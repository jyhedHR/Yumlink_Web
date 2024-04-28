<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table(name="tag", uniqueConstraints={@ORM\UniqueConstraint(name="tag_value_uniq", columns={"tag_value"})})
 * @ORM\Entity
 */
class Tag
{
    /**
     * @var int
     *
     * @ORM\Column(name="tag_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $tagId;

    /**
     * @var string
     *
     * @ORM\Column(name="tag_value", type="string", length=30, nullable=false)
     */
    private $tagValue;

    /**
     * @var int
     *
     * @ORM\Column(name="tag_nb_usage", type="integer", nullable=false)
     */
    private $tagNbUsage;


}
