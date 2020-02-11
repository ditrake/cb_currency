<?php
/**
 * 11.02.2020.
 */

declare(strict_types=1);

namespace App\Service;

use App\DataClass\ValCurs;
use App\Exception\DailyException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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

    public function __construct(string $apiUrl, HttpClientInterface $httpClient, SerializerInterface $serializer)
    {
        $this->httpClient = $httpClient;
        $this->apiUrl = $apiUrl;
        $this->serializer = $serializer;
    }

    public function daily(?string $date = null, ?string $currency = null): ?ValCurs
    {
        if ($date === null) {
            $datetime = new \DateTime();
        } else {
            $datetime = \DateTime::createFromFormat(\DateTime::ATOM, $date);
        }
        if ($datetime === false) {
            throw new DailyException(\sprintf('Date %s is wrong format, need %s', $date, \DateTime::ATOM));
        }

        if (!$this->checkDate($datetime)) {
            throw new DailyException(\sprintf('You can\'t look to the future'));
        }

//        $response = $this->makeRequest($this->makePathUrl($this->apiUrl, self::DAILY),
//            ['query' => ['date_req' => $datetime->format('d/m/Y')]]);

        //$content = $this->getContent($response);
        $content = <<<EOF
<?xml version="1.0" encoding="windows-1251"?>
    <ValCurs>
        <Valute>
            <NumCode>036</NumCode>
            <CharCode>AUD</CharCode>
            <Nominal>1</Nominal>
            <Name>asd</Name>
            <Value>42,6627</Value>
        </Valute>
        <Valute>
            <NumCode>036</NumCode>
            <CharCode>AUD</CharCode>
            <Nominal>1</Nominal>
            <Name>asd</Name>
            <Value>42,6627</Value>
        </Valute>
    </ValCurs>
EOF;

        if ($content) {
            $answer = $this->serializer->deserialize($content, ValCurs::class, 'xml', [
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
            ]);
            if (($answer !== null) && $answer instanceof ValCurs) {
                return $answer;
            }
        }

        return null;
    }

    private function checkDate(\DateTime $date): bool
    {
        return $date <= new \DateTime();
    }

    private function makePathUrl(string $baseUrl, ...$path): string
    {
        $path = \array_map(fn(&$item) => \trim((string)$item, '/'), $path);
        $fullPath = \implode('/', $path);
        $fullPath = str_replace($baseUrl, '', $fullPath);

        return \sprintf('%s/%s', \rtrim($baseUrl, '/'), $fullPath);
    }

    private function makeRequest(string $url, array $options = [], string $method = 'GET'): ?ResponseInterface
    {
        try {
            return $this->httpClient->request($method, $url, $options);
        } catch (TransportExceptionInterface $e) {
            throw new DailyException($e->getMessage());
        }
    }

    private function getContent(ResponseInterface $response): string
    {
        try {
            $code = $response->getStatusCode();
        } catch (TransportExceptionInterface $e) {
            throw new DailyException($e->getMessage());
        }

        if ($code === Response::HTTP_OK) {
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

}