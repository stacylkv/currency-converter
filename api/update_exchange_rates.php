<?php

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Bundle\FrameworkBundle\Console\Application;

// Autoload dependencies
require __DIR__ . '/../vendor/autoload.php';

// Get the token from query parameters
$token = $_GET['token'] ?? null;

// Verify the token
$secretToken = $_SERVER['UPDATE_SECRET_TOKEN'] ?? getenv('UPDATE_SECRET_TOKEN');
if ($token !== $secretToken) {
    http_response_code(403);
    echo 'Forbidden: Invalid token.';
    exit;
}

// Boot the Symfony kernel
$kernel = new \App\Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

// Create a console application instance
$application = new Application($kernel);
$application->setAutoExit(false);

// Prepare input and output for the command
$input = new ArrayInput([
    'command' => 'app:update-exchange-rates',
]);
$output = new BufferedOutput();

// Run the command
$application->run($input, $output);

// Fetch the output
$responseContent = $output->fetch();

// Return a response (Vercel requires an HTTP response)
echo $responseContent;
