<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->usePutenv(true); // <-- active la mise à jour de getenv()

if (file_exists(__DIR__ . '/.env')) {
    $dotenv->load(__DIR__ . '/.env');
}

foreach ($_ENV as $key => $value) {
    echo "$key = $value" . PHP_EOL;
}

echo 'getenv DATABASE_URL = ' . getenv('DATABASE_URL') . PHP_EOL;
echo 'getenv MY_TEST_VAR = ' . getenv('MY_TEST_VAR') . PHP_EOL;
