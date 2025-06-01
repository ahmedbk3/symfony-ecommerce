<?php
// ───────────────────────────────────────────────────────────────────────────────
// Suppress E_DEPRECATED and E_STRICT warnings before Symfony’s ErrorHandler loads
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', '1');
// ───────────────────────────────────────────────────────────────────────────────

use Symfony\Component\Dotenv\Dotenv;

// If you use a .env file, uncomment the lines below. Otherwise leave them commented.
// require dirname(__DIR__).'/.env';
// (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
