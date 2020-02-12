<?php
/**
 * 11.02.2020.
 */

declare(strict_types=1);

namespace App\DataClass;

class Valute
{
    /**
     * @var string|null
     */
    private ?string $numCode = null;
    /**
     * @var string|null
     */
    private ?string $charCode = null;
    /**
     * @var string|null
     */
    private ?string $name = null;
    /**
     * @var float|null
     */
    private ?float $value = null;
    /**
     * @var int|null
     */
    private ?int $nominal = null;

    private ?string $attrId = null;

    public function getNumCode(): ?string
    {
        return $this->numCode;
    }

    public function setNumCode(?string $numCode): self
    {
        $this->numCode = $numCode;

        return $this;
    }

    public function getCharCode(): ?string
    {
        return $this->charCode;
    }

    public function setCharCode(?string $charCode): self
    {
        $this->charCode = $charCode;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(?float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getNominal(): ?int
    {
        return $this->nominal;
    }

    public function setNominal(?int $nominal): self
    {
        $this->nominal = $nominal;

        return $this;
    }

    public function setAttrId(?string $ID): self
    {
        $this->attrId = $ID;

        return $this;
    }

    public function getAttrId(): ?string
    {
        return $this->attrId;
    }
}
