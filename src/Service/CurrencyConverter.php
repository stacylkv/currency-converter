<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CurrencyRate;

class CurrencyConverter
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function convert(float $amount, string $fromCurrency, string $toCurrency): float
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $fromRate = $this->em->getRepository(CurrencyRate::class)->find($fromCurrency);
        $toRate = $this->em->getRepository(CurrencyRate::class)->find($toCurrency);

        if (!$fromRate || !$toRate) {
            throw new \Exception('Currency rate not found.');
        }

        // Assuming rates are relative to USD
        $usdAmount = $amount / $fromRate->getRate();
        $convertedAmount = $usdAmount * $toRate->getRate();

        return $convertedAmount;
    }
}
