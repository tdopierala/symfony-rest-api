<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=50)
	 * @Assert\NotBlank
	 * @Assert\Length(
	 * 		min = 2,
	 * 		max = 50,
	 * 		minMessage = "Username must be at least {{ limit }} characters long",
	 * 		maxMessage = "Username cannot be longer than {{ limit }} characters"
	 * )
	 */
	private $name;

	/**
	 * @ORM\Column(type="string", length=180, unique=true)
	 * @Assert\NotBlank
	 * @Assert\Email(
	 * 		message = "The email '{{ value }}' is not a valid email.",
	 * 		checkMX = true
	 * )
	 * @Assert\Length(
	 * 		min = 3,
	 * 		max = 180,
	 * 		minMessage = "Email must be at least {{ limit }} characters long",
	 * 		maxMessage = "Email cannot be longer than {{ limit }} characters"
	 * )
	 */
	private $email;

	/**
	 * @ORM\Column(type="json")
	 */
	private $roles = [];

	/**
	 * @var string The hashed password
	 * @ORM\Column(type="string")
	 * @Assert\NotBlank
	 * @Assert\Length(
	 * 		min = 3,
	 * 		max = 255,
	 * 		minMessage = "Password must be at least {{ limit }} characters long",
	 * 		maxMessage = "Password cannot be longer than {{ limit }} characters"
	 * )
	 * @Assert\Regex(
	 * 		pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!?@#$%^&*()+=])[A-Za-z\d!?@#$%^&*()+=]{8,}$/",
	 * 		message="Password must meet the conditions. At least one: digit, lower case alphabet, upper case alphabet, special character (!?@#$%^&*()+=) and don't allow white spaces."
	 * )
	 */
	private $password;

	/**
	 * @ORM\Column(type="string", length=128, unique=true)
	 */
	private $token;
	
	/**
	 * @ORM\Column(type="datetime")
	 */
	private $created;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $lastlogin;
	
	public function __construct()
	{
		$this->created = new \DateTime("now");
		$this->lastlogin = new \DateTime("now");

		$this->token = \bin2hex(\random_bytes(64));

		$this->roles = $this->getRoles();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(string $email): self
	{
		$this->email = $email;

		return $this;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * A visual identifier that represents this user.
	 *
	 * @see UserInterface
	 */
	public function getUsername(): string
	{
		return (string) $this->name;
	}

	/**
	 * @see UserInterface
	 */
	public function getRoles(): array
	{
		$roles = $this->roles;
		// guarantee every user at least has ROLE_USER
		$roles[] = 'ROLE_USER';

		return array_unique($roles);
	}

	public function setRoles(array $roles): self
	{
		$this->roles = $roles;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function getPassword(): string
	{
		return (string) $this->password;
	}

	public function setPassword(string $password): self
	{
		$this->password = $password;

		return $this;
	}

	public function getToken(): ?string
	{
		return $this->token;
	}

	public function setToken(string $token): self
	{
		$this->token = $token;
		return $this;
	}
	
	public function getCreated(): ?\DateTimeInterface
	{
		return $this->created;
	}

	public function setCreated(\DateTimeInterface $created): self
	{
		$this->created = $created;
		return $this;
	}

	public function getLastlogin(): ?\DateTimeInterface
	{
		return $this->lastlogin;
	}

	public function setLastlogin(\DateTimeInterface $lastlogin): self
	{
		$this->lastlogin = $lastlogin;
		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function getSalt()
	{
		// not needed when using the "bcrypt" algorithm in security.yaml
	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials()
	{
		// If you store any temporary, sensitive data on the user, clear it here
		// $this->plainPassword = null;
	}
}
