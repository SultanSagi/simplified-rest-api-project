<?php

require_once "vendor/autoload.php";

use Dotenv\Dotenv;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$paths = [__DIR__ . "/app/Entities"];
$isDevMode = true;
$proxyDir = null;
$cache = null;

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, $proxyDir, $cache, false);

$dbParams = [
    'url' => 'mysql://mysql',
    'driver' => 'pdo_mysql',
    'user' => $_ENV['MYSQL_USER_NAME'],
    'password' => $_ENV['MYSQL_USER_PASS'],
    'dbname' => $_ENV['MYSQL_DB_NAME'],
];

$entityManager = EntityManager::create($dbParams, $config);