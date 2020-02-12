<?php
/**
 * 17.01.2020.
 */

declare(strict_types=1);

namespace App\Doctrine\Generator;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;

/**
 * Id as UUID generator.
 */
class UuidGenerator extends AbstractIdGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generate(EntityManager $em, $entity)
    {
        return \uuid_create(UUID_TYPE_TIME);
    }
}
