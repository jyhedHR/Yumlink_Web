<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserNutrition
 *
 * @ORM\Table(name="user_nutrition")
 * @ORM\Entity
 */
class UserNutrition
{
    /**
     * @var int
     *
     * @ORM\Column(name="age", type="integer", nullable=false)
     */
    private $age;

    /**
     * @var float
     *
     * @ORM\Column(name="weight", type="float", precision=10, scale=0, nullable=false)
     */
    private $weight;

    /**
     * @var float
     *
     * @ORM\Column(name="height", type="float", precision=10, scale=0, nullable=false)
     */
    private $height;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_level", type="string", length=11, nullable=false)
     */
    private $activityLevel;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=10, nullable=false)
     */
    private $gender;

    /**
     * @var float
     *
     * @ORM\Column(name="calorie", type="float", precision=10, scale=0, nullable=false)
     */
    private $calorie;

    /**
     * @var float
     *
     * @ORM\Column(name="protein", type="float", precision=10, scale=0, nullable=false)
     */
    private $protein;

    /**
     * @var float
     *
     * @ORM\Column(name="carbs", type="float", precision=10, scale=0, nullable=false)
     */
    private $carbs;

    /**
     * @var float
     *
     * @ORM\Column(name="fat", type="float", precision=10, scale=0, nullable=false)
     */
    private $fat;

    /**
     * @var \User
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="idU")
     * })
     */
    private $id;


}
