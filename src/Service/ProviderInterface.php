<?php
/**
 * 11.02.2020.
 */

declare(strict_types=1);


namespace App\Service;


use App\DataClass\ValCurs;

interface ProviderInterface
{
    public function daily(?string $date = null, ?string $currency = null): ?ValCurs;
}