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
}