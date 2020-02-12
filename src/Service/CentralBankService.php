<?php
/**
 * 11.02.2020.
 */

declare(strict_types=1);

namespace App\Service;

use App\DataClass\ValCurs;
use App\DataClass\Valute;
use App\Entity\Currency;
use App\Exception\DailyException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CentralBankService implements ProviderInterface
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;
    /**
     * @var string
     */
    private $apiUrl;
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;
    /**
     * @var string
     */
    private const DAILY = '/XML_daily.asp';
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * CentralBankService constructor.
     */
    public function __construct(string $apiUrl, HttpClientInterface $httpClient, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $this->httpClient = $httpClient;
        $this->apiUrl = $apiUrl;
        $this->serializer = $serializer;
        $this->em = $em;
    }

    /**
     * @return Currency|array|null
     *
     * @throws DailyException
     */
    public function daily(?string $date = null, ?string $currency = null)
    {
        if (null === $date) {
            $datetime = new \DateTime();
        } else {
            $datetime = \DateTime::createFromFormat('d/m/Y', $date);
        }
        $datetime->setTime(0, 0, 0);
        if (false === $datetime) {
            throw new DailyException(\sprintf('Date %s is wrong format, need %s', $date, \DateTime::ATOM));
        }

        if (!$this->checkDate($datetime)) {
            throw new DailyException(\sprintf('You can\'t look to the future'));
        }
        if ($this->checkExist($datetime)) {
            $result = $this->getExist($datetime, $currency);
        } else {
            $response = $this->request($datetime);
            $result = $this->setExist($response, $currency);
        }

        return $result;
    }

    /**
     * @throws \Exception
     */
    private function checkDate(\DateTime $date): bool
    {
        return $date <= new \DateTime();
    }

    /**
     * @param mixed ...$path
     */
    private function makePathUrl(string $baseUrl, ...$path): string
    {
        $path = \array_map(fn (&$item) => \trim((string) $item, '/'), $path);
        $fullPath = \implode('/', $path);
        $fullPath = str_replace($baseUrl, '', $fullPath);

        return \sprintf('%s/%s', \rtrim($baseUrl, '/'), $fullPath);
    }

    /**
     * @throws DailyException
     */
    private function makeRequest(string $url, array $options = [], string $method = 'GET'): ?ResponseInterface
    {
        try {
            return $this->httpClient->request($method, $url, $options);
        } catch (TransportExceptionInterface $e) {
            throw new DailyException($e->getMessage());
        }
    }

    /**
     * @throws DailyException
     */
    private function getContent(ResponseInterface $response): string
    {
        try {
            $code = $response->getStatusCode();
        } catch (TransportExceptionInterface $e) {
            throw new DailyException($e->getMessage());
        }

        if (Response::HTTP_OK === $code) {
            try {
                return $response->getContent();
            } catch (ClientExceptionInterface $e) {
                throw new DailyException($e->getMessage());
            } catch (RedirectionExceptionInterface $e) {
                throw new DailyException($e->getMessage());
            } catch (ServerExceptionInterface $e) {
                throw new DailyException($e->getMessage());
            } catch (TransportExceptionInterface $e) {
                throw new DailyException($e->getMessage());
            }
        }
    }

    /**
     * @return bool
     */
    private function checkExist(\DateTime $dateTime)
    {
        $repo = $this->em->getRepository(Currency::class);

        return $repo->checkExist($dateTime);
    }

    /**
     * @throws DailyException
     */
    private function request(\DateTime $dateTime): ?ValCurs
    {
        $response = $this->makeRequest($this->makePathUrl($this->apiUrl, self::DAILY),
            ['query' => ['date_req' => $dateTime->format('d/m/Y')]]);

        $content = $this->getContent($response);

        if ($content) {
            $answer = $this->serializer->deserialize($content, ValCurs::class, 'xml', [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
            ]);
            if ((null !== $answer) && $answer instanceof ValCurs) {
                return $answer;
            }
        }

        return null;
    }

    /**
     * @param string|null $currency
     */
    private function setExist(ValCurs $curs, ?string $currencyCode = null): array
    {
        $valutes = $curs->getValute();
        $date = \DateTime::createFromFormat('d.m.Y', $curs->getAttrDate());
        $date->setTime(0, 0, 0);
        $result = [];
        /** @var Valute $valute */
        foreach ($valutes as $valute) {
            $currency = new Currency();
            $currency->setName($valute->getName());
            $currency->setValue($valute->getValue());
            $currency->setCharCode($valute->getCharCode());
            $currency->setNominal($valute->getNominal());
            $currency->setExtId($valute->getAttrId());
            $currency->setCreatedAt($date);
            if (null !== $currency) {
                if ($valute->getCharCode() === $currencyCode) {
                    $result[] = $currency;
                }
            } else {
                $result[] = $currency;
            }

            $this->em->persist($currency);
        }
        $this->em->flush();

        return $result;
    }

    /**
     * @return Currency|array|null
     */
    private function getExist(\DateTime $dateTime, ?string $currencyCode = null)
    {
        $repo = $this->em->getRepository(Currency::class);

        if (null !== $currencyCode) {
            return $repo->findByCurrencyCodeOnDate($currencyCode, $dateTime);
        }

        return $repo->findCurrencyByDate($dateTime);
    }
}
