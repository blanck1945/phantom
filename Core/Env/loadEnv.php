<?php

use Dotenv\Dotenv;

/*
|--------------------------------------------------------------------------
| Load environment variables
|--------------------------------------------------------------------------
|
| Load the environment variables from the .env file
|
*/

$dotenv = Dotenv::createImmutable(ENV_PATH);
$dotenv->load();
