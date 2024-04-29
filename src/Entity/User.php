<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 * @UniqueEntity(fields={"email"}, message="Cet e-mail est déjà utilisé.")
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="unique_email", columns={"email"})}, indexes={@ORM\Index(name="fk_idA", columns={"idA"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="idU", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $idu;
     /**
     * @var string
     * @Assert\NotBlank(message="Le nom est requis.")
     * @Assert\Length(max=20, maxMessage="Le nom ne peut pas dépasser {{ limit }} caractères.")
     * @ORM\Column(name="nom", type="string", length=20, nullable=false)
     */
    private $nom;

     /**
     * @var string
     * @Assert\NotBlank(message="Le prénom est requis.")
     * @Assert\Length(max=20, maxMessage="Le prénom ne peut pas dépasser {{ limit }} caractères.")
     * @ORM\Column(name="prenom", type="string", length=20, nullable=false)
     */
    private $prenom;

 /**
     * @var string
     * @Assert\NotBlank(message="L'e-mail est requis.")
     * @Assert\Email(message="L'adresse e-mail n'est pas valide.")
     * @ORM\Column(name="email", type="string", length=50, nullable=false)
     */
    private $email;

    /**
     * @var string The hashed password
     * @Assert\NotBlank(message="Le mot de passe est requis.")
     * @Assert\Length(min=8, minMessage="Le mot de passe doit contenir au moins {{ limit }} caractères.")
     * @Assert\Regex(
     *      pattern="/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/",
     *      message="Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial."
     * )
     * @ORM\Column(name="mdp", type="string", length=150, nullable=false)
     */
    private $mdp;

     /**
     * @var int
     * @Assert\NotBlank(message="Le numéro de téléphone est requis.")
     * @Assert\Length(min=8, max=8, exactMessage="Le numéro de téléphone doit contenir exactement {{ limit }} chiffres.")
     * @Assert\Regex(
     *      pattern="/^[2-5|9]\d{7}$/",
     *      message="Le numéro de téléphone n'est pas valide."
     * )
     * @ORM\Column(name="tel", type="integer", nullable=false)
     */
    private $tel;       

    /**
     * @var string
     
     * @ORM\Column(name="role", type="string", length=20, nullable=false)
     */
    private $role;

    /**
     * @var string
     * @ORM\Column(name="Image", type="string", length=100, nullable=true)
     */
    private $image;
    /**
     * @var bool|null
     *
     * @ORM\Column(name="blocked", type="boolean", nullable=true)
     */
    private $blocked;
     /**
    
     * @ORM\OneToOne(targetEntity="Adresse")
     * @ORM\JoinColumn(name="idA", referencedColumnName="idA")
     */
    private ?Adresse $adresse;
 

    public function getIdu(): ?int
    {
        return $this->idu;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }
    public function isBlocked(): ?bool
    {
        return $this->blocked;
    }

    public function setBlocked(bool $blocked): self
    {
        $this->blocked = $blocked;

        return $this;
    }
    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): static
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

   

    public function setAdresse(?Adresse $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }
    public function getAdresseId(): ?int
{
    return $this->adresse ? $this->adresse->getIda() : null;
}


    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return [$this->role];
    }

    public function setRoles(array $roles): static
    {
        $this->role = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->mdp;
    }

    public function setPassword(string $password): static
    {
        $this->mdp = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
