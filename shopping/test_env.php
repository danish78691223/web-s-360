<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo getenv('BREVO_SMTP_HOST'); // Replace with an actual key from your .env file

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Environment variables loaded successfully!";

?>
