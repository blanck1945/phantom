<?php

namespace config;

/*
|--------------------------------------------------------------------------
| Project Root
|--------------------------------------------------------------------------
|
| This constant is the root of the project
|
*/

define('PROJECT_ROOT', dirname(__DIR__));

/*
|--------------------------------------------------------------------------
| View Path
|--------------------------------------------------------------------------
|
| This constant is the path to the views directory
|
*/

define('VIEW_PATH', PROJECT_ROOT . '/views/pages/');

/*
|--------------------------------------------------------------------------
| Cache Directory
|--------------------------------------------------------------------------
|
| This constant is the path to the cache directory
|
*/

define('CACHE_DIR', PROJECT_ROOT . '/Core/Cache/views/');

/*
|--------------------------------------------------------------------------
| JSON Cache File
|--------------------------------------------------------------------------
|
| This constant is the path to the json cache file
|
*/

define('JSON_CACHE_FILE', PROJECT_ROOT . '/Core/Cache/views/directory.json');

/*
|--------------------------------------------------------------------------
| Environment Path
|--------------------------------------------------------------------------
|
| This constant is the path to the environment file
|
*/

define('ENV_PATH', PROJECT_ROOT);

/*
|--------------------------------------------------------------------------
| Controllers Path
|--------------------------------------------------------------------------
|
| This constant is the path to the controllers directory
|
*/

define('CONTROLLERS_PATH',  PROJECT_ROOT . '/app/Http/Controller/');

/*
|--------------------------------------------------------------------------
| Migrations Path
|--------------------------------------------------------------------------
|
| This constant is the path to the migrations directory
|
*/

define('MIGRATIONS_PATH',  PROJECT_ROOT . '/database/migrations/');
