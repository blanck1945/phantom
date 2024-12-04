<?

use Controller\ViewController\ViewModule;
use Core\Request\Request;
use Core\Router\Router;
use PHPUnit\Framework\TestCase;


class ViewModuleTest extends TestCase
{
    /** @test */
    public function routes_should_be_registers()
    {
        $request = new Request();
        $router = new Router($request);

        $router->register([
            ViewModule::class,
        ]);

        $expectedRoutes = [
            'Controller\ViewController\ViewModule'
        ];

        $expectedNoQueryRoutes = [
            '/' => [
                'GET' => 'home',
                'POST' => 'home'
            ],
            '/about' => [
                'GET' =>  'about'
            ],
            '/ff' => [
                'GET' => 'get_ff_data'
            ]
        ];

        $expectedQueryRoutes = [
            '/:name' => [
                'GET' => 'var_name'
            ],
            '/name/:name' => [
                'GET' => 'var_name'
            ]
        ];

        $classRoutes = $router->get_routes();
        $classNoQueryRoutes = $router->get_no_query_routes();
        $classQueryRoutes = $router->get_query_routes();

        ## Routes assertions
        $this->assertIsArray($classRoutes);
        $this->assertEquals($expectedRoutes, $classRoutes);

        ## No Query Routes assertions
        $this->assertIsArray($classNoQueryRoutes);
        $this->assertEquals($expectedNoQueryRoutes, $classNoQueryRoutes);

        ## Query Routes assertions
        $this->assertIsArray($classQueryRoutes);
        $this->assertEquals($expectedQueryRoutes, $classQueryRoutes);
    }
}
