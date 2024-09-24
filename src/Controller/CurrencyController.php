<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CurrencyRateRepository;


class CurrencyController extends AbstractController
{
    #[Route('/currencies', name: 'currencies')]
    public function index(CurrencyRateRepository $currencyRateRepository)
    {
        $rates = $currencyRateRepository->findAll();

        return $this->render('currencies/index.html.twig', [
            'rates' => $rates,
        ]);
    }
}
