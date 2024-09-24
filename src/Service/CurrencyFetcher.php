<?php

/**
 * @license PROPRIETARY
 *
 * @author  Anastasiia Lukianova <stacylkv@gmail.com>
 */

namespace App\Service;

use GuzzleHttp\Client;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CurrencyRate;

class CurrencyFetcher
{
    private $client;
    private $em;
    private $currencies;
    private $apiKey;

    public function __construct(EntityManagerInterface $em, array $currencies, string $apiKey)
    {
        $this->client = new Client(['base_uri' => 'https://api.freecurrencyapi.com/v1/']);
        $this->em = $em;
        $this->currencies = $currencies;
        $this->apiKey = $apiKey;
    }

    public function fetchAndStoreRates()
    {
        $symbols = implode(',', $this->currencies);

        $response = $this->client->request('GET', 'latest', [
            'query' => [
                'apikey' => $this->apiKey,
                'currencies' => $symbols,
                'base_currency' => 'USD',
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        foreach ($data['data'] as $currencyCode => $rate) {
            $currencyRate = $this->em->getRepository(CurrencyRate::class)->find($currencyCode);

            if (!$currencyRate) {
                $currencyRate = new CurrencyRate();
                $currencyRate->setCurrencyCode($currencyCode);
            }

            $currencyRate->setRate($rate);

            $this->em->persist($currencyRate);
        }

        $this->em->flush();
    }
}
