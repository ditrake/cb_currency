<?php
/**
 * 11.02.2020.
 */

declare(strict_types=1);


namespace App\DataClass;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ValCurs
{
    /**
     * @var Collection|Valute[]
     */
    private Collection $valute;

    /**
     * @var string
     */
    private string $attrDate;

    /**
     * @var string
     */
    private string $attrName;

    public function __construct()
    {
        $this->valute = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getValute(): Collection
    {
        return $this->valute;
    }

    /**
     * @param Valute[]|Collection $valute
     */
    public function setValute($valute): void
    {
        $this->valute = $valute instanceof ArrayCollection ? $valute : new ArrayCollection($valute);
    }

    /**
     * @param string $date
     * @return self
     */
    public function setAttrDate(string $date): self
    {
        $this->attrDate = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getAttrDate(): string
    {
        return $this->attrDate;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setAttrName(string $name): self
    {
        $this->attrName = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getAttrName(): string
    {
        return $this->attrName;
    }
}