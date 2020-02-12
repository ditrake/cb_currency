<?php

namespace App\Entity;

use App\Doctrine\Traits\UuidPrimaryKey;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CurrencyRepository")
 */
class Currency
{
    use UuidPrimaryKey;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $value;

    /**
     * @ORM\Column(type="integer")
     */
    private $nominal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $extId;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $charCode;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getNominal(): ?int
    {
        return $this->nominal;
    }

    public function setNominal(int $nominal): self
    {
        $this->nominal = $nominal;

        return $this;
    }

    public function getExtId(): ?string
    {
        return $this->extId;
    }

    public function setExtId(string $extId): self
    {
        $this->extId = $extId;

        return $this;
    }

    public function getCharCode(): ?string
    {
        return $this->charCode;
    }

    public function setCharCode(string $charCode): self
    {
        $this->charCode = $charCode;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
