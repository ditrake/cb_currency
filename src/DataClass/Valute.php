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

    /**
     * @return string|null
     */
    public function getNumCode(): ?string
    {
        return $this->numCode;
    }

    /**
     * @param string|null $numCode
     * @return self
     */
    public function setNumCode(?string $numCode): self
    {
        $this->numCode = $numCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCharCode(): ?string
    {
        return $this->charCode;
    }

    /**
     * @param string|null $charCode
     * @return self
     */
    public function setCharCode(?string $charCode): self
    {
        $this->charCode = $charCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getValue(): ?float
    {
        return $this->value;
    }

    /**
     * @param float|null $value
     * @return self
     */
    public function setValue(?float $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getNominal(): ?int
    {
        return $this->nominal;
    }

    /**
     * @param int|null $nominal
     * @return self
     */
    public function setNominal(?int $nominal): self
    {
        $this->nominal = $nominal;
        return $this;
    }

    /**
     * @param string|null $ID
     * @return self
     */
    public function setAttrId(?string $ID): self
    {
        $this->attrId = $ID;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAttrId(): ?string
    {
        return $this->attrId;
    }
}