<?php

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Register The Global Definitions
|--------------------------------------------------------------------------
|
| We'll register the global definitions here so we can use them in the
| application. This will allow us to use the global definitions
| in the application.
|
*/

require __DIR__ . '/../config/globalDefinitions.php';

/*
|--------------------------------------------------------------------------
| Load the environment variables
|--------------------------------------------------------------------------
|
| We'll load the environment variables here so we can use them in the
| application. This will allow us to use the environment variables
| in the application.
|
*/

require __DIR__ . '/../Core/Env/loadEnv.php';

/*
|--------------------------------------------------------------------------
| Boostrap the application
|--------------------------------------------------------------------------
|
| This is where the application is boostrapped. We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__ . '/../boostrap/app.php';


// opcache.enable=1
// opcache.memory_consumption=128
// opcache.interned_strings_buffer=8
// opcache.max_accelerated_files=10000
// opcache.revalidate_freq=0