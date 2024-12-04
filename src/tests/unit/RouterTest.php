
<?

use Core\Request\Request;
use Core\Router\Router;
use PHPUnit\Framework\TestCase;


class TestModule
{
    static public $controller = ViewController::class;

    static public function config()
    {
        return [
            'metadata' => false,
        ];
    }

    static public function inject()
    {
        return [];
    }

    static public function routes()
    {
        return  [
            'routes' => [
                '/test' => [
                    'GET' => 'some_method',
                ],
                '/:test' => [
                    'GET' => 'some_method'
                ],
            ]
        ];
    }
}

class RouterTest extends TestCase
{
    private $request;
    private $router;

    public function setUp(): void
    {
        parent::setUp();

        $this->request = new Request();
        $this->router = new Router($this->request);
    }

    /** @test */
    public function test_routes_are_properly_register()
    {
        $this->router->register([
            TestModule::class,
        ]);

        $expectedRoutes = [
            TestModule::class,
        ];

        $expectedNoQueryRoutes = [
            '/test' => [
                'GET' => 'some_method',
            ],
        ];

        $expectedQueryRoutes = [
            '/:test' => [
                'GET' => 'some_method'
            ],
        ];

        $classRoutes =  $this->router->get_routes();
        $classNoQueryRoutes =  $this->router->get_no_query_routes();
        $classQueryRoutes =  $this->router->get_query_routes();

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
