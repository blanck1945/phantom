<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../public/server.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

server();
