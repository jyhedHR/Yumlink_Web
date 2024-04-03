<?php

namespace App\Entity;
use App\Repository\UserNutritionRepository;
use Symfony\Component\Validator\Constraints as Assert;


use Doctrine\ORM\Mapping as ORM;

/**
 * UserNutrition
 *
 * @ORM\Table(name="user_nutrition")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\UserNutritionRepository")
 */
class UserNutrition
{
    /**
     * @var int
     *
     * @ORM\Column(name="age", type="integer", nullable=false)
      * @Assert\NotBlank
     * @Assert\Type(type="integer")
     * @Assert\Range(min=18, max=99)
     */
    private $age;

    /**
     * @var float
     *
     * @ORM\Column(name="weight", type="float", precision=10, scale=0, nullable=false)
     * @Assert\NotBlank
     * @Assert\Type(type="float")
     * @Assert\Range(min=45, max=200)
     */
    private $weight;

    /**
     * @var float
     *
     * @ORM\Column(name="height", type="float", precision=10, scale=0, nullable=false)
     * @Assert\NotBlank
     * @Assert\Type(type="float")
     * @Assert\Range(min=110, max=230)
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
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="User")
     *   @ORM\JoinColumn(name="id", referencedColumnName="idU")
     */
    private ?User $user;
    
    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getActivityLevel(): ?string
    {
        return $this->activityLevel;
    }

    public function setActivityLevel(string $activityLevel): static
    {
        $this->activityLevel = $activityLevel;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getCalorie(): ?float
    {
        return $this->calorie;
    }

    public function setCalorie(float $calorie): static
    {
        $this->calorie = $calorie;

        return $this;
    }

    public function getProtein(): ?float
    {
        return $this->protein;
    }

    public function setProtein(float $protein): static
    {
        $this->protein = $protein;

        return $this;
    }

    public function getCarbs(): ?float
    {
        return $this->carbs;
    }

    public function setCarbs(float $carbs): static
    {
        $this->carbs = $carbs;

        return $this;
    }

    public function getFat(): ?float
    {
        return $this->fat;
    }

    public function setFat(float $fat): static
    {
        $this->fat = $fat;

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
public function __toString(): string
    {
        // Retourne une représentation string de l'objet, par exemple le nom de l'aéroport
        return $this->age;
    }

    public function calculateTDEE(): float
    {
        $bmr = ($this->gender === 'male') ? (10 * $this->weight + 6.25 * $this->height - 5 * $this->age + 5) : (10 * $this->weight + 6.25 * $this->height - 5 * $this->age - 161);

        $activityFactors = [
            'sedentary' => 1.2,
            'lightly_active' => 1.375,
            'moderately_active' => 1.55,
            'very_active' => 1.725,
            'extra_active' => 1.9
        ];

        $activityFactor = $activityFactors[$this->activityLevel];

        return $bmr * $activityFactor;
    }

    // Function to distribute calories into macronutrients based on user input
    public function distributeMacronutrients(): array
    {
        // Assuming a standard macronutrient distribution
        $proteinRatio = 0.3; // 30%
        $carbRatio = 0.4; // 40%
        $fatRatio = 0.3; // 30%
    
        $totalCalories = $this->calorie;
    
        $proteinCalories = $totalCalories * $proteinRatio;
        $carbCalories = $totalCalories * $carbRatio;
        $fatCalories = $totalCalories * $fatRatio;
    
        return [
            'calorie' => $totalCalories, // Total calories
            'protein' => $proteinCalories / 4, // Convert protein calories to grams
            'carbs' => $carbCalories / 4, // Convert carb calories to grams
            'fat' => $fatCalories / 9 // Convert fat calories to grams
        ];
    }
}
