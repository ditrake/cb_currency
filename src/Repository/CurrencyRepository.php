<?php

namespace App\Repository;

use App\Entity\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Currency|null find($id, $lockMode = null, $lockVersion = null)
 * @method Currency|null findOneBy(array $criteria, array $orderBy = null)
 * @method Currency[]    findAll()
 * @method Currency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    public function findByCurrencyCodeOnDate(string $currency, \DateTime $dateTime): ?Currency
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.charCode = :code')
            ->andWhere('c.createdAt = :date')
            ->setParameter('code', $currency)
            ->setParameter('date', $dateTime)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findCurrencyByDate(\DateTime $dateTime): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.createdAt = :date')
            ->setParameter('date', $dateTime)
            ->getQuery()
            ->getResult();
    }

    public function checkExist(\DateTime $dateTime): bool
    {
        $result = $this->createQueryBuilder('c')
            ->andWhere('c.createdAt = :date')
            ->setParameter('date', $dateTime)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return null !== $result;
    }
}
