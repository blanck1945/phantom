
<?php

use Controller\ViewController\ViewController;
use Controller\ViewController\ViewModule;
use Core\Phantom;
use Core\Request\PhantomRequest;
use Core\Request\Request;
use Core\Response\PhantomResponse;
use Core\Router\Router;
use Guards\IsAdmin;
use Guards\IsWorkingTime;
use PHPUnit\Framework\TestCase;

// class BrowserRequestMock
// {
//     public function __construct($method, $uri)
//     {
//         $_SERVER['REQUEST_METHOD'] = $method;
//         $_SERVER['REQUEST_URI'] = $uri;
//     }
// }

// class TestModule
// {
//     static public $controller = ViewController::class;

//     static public function config()
//     {
//         return [
//             'metadata' => false,
//         ];
//     }

//     static public function inject()
//     {
//         return [];
//     }

//     static public function routes()
//     {
//         return  [
//             'routes' => [
//                 '/test' => [
//                     'GET' => 'some_method',
//                 ],
//                 '/:test' => [
//                     'GET' => 'some_method'
//                 ],
//             ]
//         ];
//     }
// }

class RouterTest extends TestCase
{
    private Phantom $app;
    private Router $router;

    protected function setUp(): void
    {
        parent::setUp();
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/ff';
        $this->app = new Phantom();
        $this->router = $this->app->get_router();
    }

    /**
     * @test
     */
    public function there_is_no_routes_when_created()
    {
        $newRouter = new Router(new PhantomRequest(), null);

        $this->assertEmpty($newRouter->get_handler());
    }

    /**
     * @test
     */
    public function set_routes()
    {
        $this->app->register_routes_map(ViewModule::class);

        $this->assertEquals('get_ff_data', $this->router->get_handler());
    }

    /**
     * @test
     */
    public function check_exeption_when_no_route_match()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/no_route';

        $this->assertEmpty($this->router->get_handler());

        $this->expectException(Exception::class);

        $this->app->register_routes_map(ViewModule::class);
    }
}
