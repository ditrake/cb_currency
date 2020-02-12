<?php
/**
 * 17.01.2020.
 */

declare(strict_types=1);

namespace App\Doctrine\Traits;

use App\Doctrine\Generator\UuidGenerator;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait UuidPrimaryKey.
 * Use it in Doctrine entities.
 */
trait UuidPrimaryKey
{
    /**
     * @var string|null
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    protected ?string $id;

    public function getId(): ?string
    {
        return $this->id;
    }
}
