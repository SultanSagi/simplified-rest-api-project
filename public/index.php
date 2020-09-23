<?php

use Dotenv\Dotenv;

define('APP', dirname(__DIR__));
chdir(APP);

require 'vendor/autoload.php';

require_once "bootstrap.php";

$dotenv = Dotenv::createImmutable(APP);
$dotenv->load();

require 'router/routes.php';