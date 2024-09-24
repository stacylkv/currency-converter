<?php

namespace App\Command;

use App\Service\CurrencyFetcher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:update-exchange-rates',
    description: 'Updates currency exchange rates.',
)]
class UpdateExchangeRatesCommand extends Command
{
    private $currencyFetcher;

    public function __construct(CurrencyFetcher $currencyFetcher)
    {
        parent::__construct();
        $this->currencyFetcher = $currencyFetcher;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->currencyFetcher->fetchAndStoreRates();
        $output->writeln('Exchange rates updated successfully.');

        return Command::SUCCESS;
    }
}
