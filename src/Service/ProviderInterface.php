<?php
/**
 * 11.02.2020.
 */

declare(strict_types=1);


namespace App\Service;


use App\DataClass\ValCurs;
use App\Entity\Currency;

interface ProviderInterface
{
    /**
     * @param string|null $date
     * @param string|null $currency
     * @return Currency|array|null
     */
    public function daily(?string $date = null, ?string $currency = null);
}