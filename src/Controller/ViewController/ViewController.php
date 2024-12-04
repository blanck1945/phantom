<?php

namespace Controller\ViewController;

use Core\Database\Database;

class ViewController
{

    public function __construct(private Database $databaseService) {}

    public function var_name($request)
    {
        $name = $request->getParam('name');

        return [
            'view' => 'name.php',
            'name' => $name
        ];
    }

    public function home()
    {
        return [
            'view' => 'home.blade.php',
            'hello' => 'Hello World',
            'framework' => 'Phantom'
        ];
    }

    public function about()
    {
        return [
            'view' => 'product.php',
            'variables' => [
                'hello' => 'Hello World',
                'framework' => 'Phantom',
                'id' => 1,
                'name' => 'Product 1',
            ]
        ];
    }

    public function get_ff_data()
    {
        // $db = 'kanas';
        // $user = 'admin';
        // $password = '1234567';
        // $dsn = "pgsql:host=db;port=5432;dbname=$db;";
        // // make a database connection
        // $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        // if ($pdo) {
        //     echo "Connected to the $db database successfully!";
        // }

        return [
            'name' => 'Final Fantasy 7',
            'release_date' => '1997-01-31',
            'console' => 'Playstation',
            'protagonist' => 'Cloud Strife',
            'antagonist' => 'Sephiroth',
        ];
    }
}
