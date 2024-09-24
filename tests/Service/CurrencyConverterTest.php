<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\CurrencyConverter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use App\Entity\CurrencyRate;

class CurrencyConverterTest extends TestCase
{
    public function testConvertSuccess()
    {
        // Arrange
        $amount = 100;
        $fromCurrency = 'USD';
        $toCurrency = 'EUR';

        $fromRate = new CurrencyRate();
        $fromRate->setCurrencyCode('USD');
        $fromRate->setRate(1.0);

        $toRate = new CurrencyRate();
        $toRate->setCurrencyCode('EUR');
        $toRate->setRate(0.85);

        $currencyRateRepository = $this->createMock(ObjectRepository::class);
        $currencyRateRepository->expects($this->any())
            ->method('find')
            ->willReturnMap([
                [$fromCurrency, $fromRate],
                [$toCurrency, $toRate],
            ]);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($currencyRateRepository);

        $converter = new CurrencyConverter($entityManager);

        // Act
        $convertedAmount = $converter->convert($amount, $fromCurrency, $toCurrency);

        // Assert
        $expectedAmount = $amount * $toRate->getRate() / $fromRate->getRate();
        $this->assertEquals($expectedAmount, $convertedAmount);
    }

    public function testConvertSameCurrency()
    {
        // Arrange
        $amount = 100;
        $currency = 'USD';

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $converter = new CurrencyConverter($entityManager);

        // Act
        $convertedAmount = $converter->convert($amount, $currency, $currency);

        // Assert
        $this->assertEquals($amount, $convertedAmount);
    }

    public function testConvertCurrencyRateNotFound()
    {
        // Arrange
        $amount = 100;
        $fromCurrency = 'USD';
        $toCurrency = 'XYZ';

        $fromRate = new CurrencyRate();
        $fromRate->setCurrencyCode('USD');
        $fromRate->setRate(1.0);

        $currencyRateRepository = $this->createMock(ObjectRepository::class);
        $currencyRateRepository->method('find')
            ->willReturnMap([
                [$fromCurrency, $fromRate],
                [$toCurrency, null],
            ]);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('getRepository')
            ->willReturn($currencyRateRepository);

        $converter = new CurrencyConverter($entityManager);

        // Assert Exception
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Currency rate not found.');

        // Act
        $converter->convert($amount, $fromCurrency, $toCurrency);
    }

    public function testConvertZeroAmount()
    {
        // Arrange
        $amount = 0;
        $fromCurrency = 'USD';
        $toCurrency = 'EUR';

        $fromRate = new CurrencyRate();
        $fromRate->setCurrencyCode('USD');
        $fromRate->setRate(1.0);

        $toRate = new CurrencyRate();
        $toRate->setCurrencyCode('EUR');
        $toRate->setRate(0.85);

        $currencyRateRepository = $this->createMock(ObjectRepository::class);
        $currencyRateRepository->method('find')
            ->willReturnMap([
                [$fromCurrency, $fromRate],
                [$toCurrency, $toRate],
            ]);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('getRepository')
            ->willReturn($currencyRateRepository);

        $converter = new CurrencyConverter($entityManager);

        // Act
        $convertedAmount = $converter->convert($amount, $fromCurrency, $toCurrency);

        // Assert
        $this->assertEquals(0, $convertedAmount);
    }
}
