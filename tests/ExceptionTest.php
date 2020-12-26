<?php

use Dmn\Exceptions\Example\Controllers\TestController;
use Dmn\Exceptions\Example\MergeMetaException;
use Dmn\Exceptions\Example\Models\TestModel;
use Dmn\Exceptions\Example\Models\TestModelWithResourceName;
use Dmn\Exceptions\TokenExpiredException;
use Dmn\Exceptions\UnauthorizedException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Laravel\Lumen\Http\Request;
use Laravel\Lumen\Testing\TestCase;

class ExceptionTest extends TestCase
{
    /**
     * @inheritDoc
     */
    public function createApplication()
    {
        return require __DIR__ . '/bootstrap.php';
    }

    /**
     * Setup
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $path = __DIR__ . '/../src/config/validation.php';
        $this->app->make('config')->set('validation', require $path);
    }

    /**
     * Run the database migrations for the application.
     *
     * @return void
     */
    public function runDatabaseMigrations(): void
    {
        $migrationPath = __DIR__ . '/database/migrations';

        $this->artisan(
            'migrate:fresh --realpath --path="'
            . $migrationPath
            . '"'
        );

        $this->beforeApplicationDestroyed(function () use ($migrationPath) {
            $this->artisan(
                'migrate:rollback --realpath --path="'
                . $migrationPath
                . '"'
            );
        });
    }

    /**
     * @test
     * @testdox Not found response
     *
     * @return void
     */
    public function routeNotFound(): void
    {
        $this->app->router->get('/', function () {
            return response('Hello World');
        });

        $response = $this->app->handle(Request::create('/foo', 'GET'));

        $jsonResponse = json_decode($response->content(), true);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertArrayHasKey('error', $jsonResponse);
        $this->assertEquals('http_not_found', $jsonResponse['error']);
        $this->assertArrayHasKey('message', $jsonResponse);
        $this->assertArrayHasKey('error_description', $jsonResponse);
    }

    /**
     * @test
     * @testdox Method not allowed response
     *
     * @return void
     */
    public function methodNotAllowed(): void
    {
        $this->app->router->get('/', function () {
            return response('Hello World');
        });

        $response = $this->app->handle(Request::create('/', 'POST'));

        $jsonResponse = json_decode($response->content(), true);
        $this->assertEquals(405, $response->getStatusCode());
        $this->assertArrayHasKey('error', $jsonResponse);
        $this->assertEquals('method_not_allowed', $jsonResponse['error']);
        $this->assertArrayHasKey('message', $jsonResponse);
        $this->assertArrayHasKey('error_description', $jsonResponse);
    }

    /**
     * @test
     * @testdox Model not found
     *
     * @return void
     */
    public function modelNotFound(): void
    {
        $this->runDatabaseMigrations();
        TestModel::create(['name' => 'test']);
        $this->app->router->get('/', function () {
            return TestModel::findOrFail(2);
        });

        $response = $this->app->handle(Request::create('/', 'GET'));

        $jsonResponse = json_decode($response->content(), true);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertArrayHasKey('error', $jsonResponse);
        $this->assertEquals('resource_not_found', $jsonResponse['error']);
        $this->assertArrayHasKey('message', $jsonResponse);
        $this->assertArrayHasKey('error_description', $jsonResponse);
        $this->assertEquals('Resource not found.', $jsonResponse['message']);
    }

    /**
     * @test
     * @testdox Model not found with resource name
     *
     * @return void
     */
    public function modelNotFoundWithResourceName(): void
    {
        $this->runDatabaseMigrations();
        TestModelWithResourceName::create(['name' => 'test']);
        $this->app->router->get('/', function () {
            return TestModelWithResourceName::findOrFail(2);
        });

        $response = $this->app->handle(Request::create('/', 'GET'));

        $jsonResponse = json_decode($response->content(), true);
        $this->assertEquals('Test not found.', $jsonResponse['message']);
    }

    /**
     * @test
     * @testdox Unprocessable entity
     *
     * @return void
     */
    public function unprocessableEntity(): void
    {
        $this->app->router->post('/', [
            'as' => 'reference.name',
            'uses' => TestController::class . '@test'
        ]);

        $this->json('POST', '/');

        $this->response->assertJsonValidationErrors([
            'name' => 'The name field is required'
        ]);

        $jsonResponse = $this->response->json();
        $this->assertArrayHasKey('error', $jsonResponse);
        $this->assertEquals('unprocessable_entity', $jsonResponse['error']);
        $this->assertArrayHasKey('message', $jsonResponse);
        $this->assertArrayHasKey('error_description', $jsonResponse);
        $this->assertArrayHasKey('meta', $jsonResponse);
    }

    /**
     * @test
     * @testdox Unexpected Error
     *
     * @return void
     */
    public function unexpectedError()
    {
        $this->app->router->post('/', function () {
            throw new MergeMetaException();
        });

        $this->post('/');

        $this->assertResponseStatus(400);

        $jsonResponse = $this->response->json();
        $this->assertEquals('unexpected_error', $jsonResponse['error']);
        $this->assertEquals('Unexpected error.', $jsonResponse['message']);
        $this->assertEquals('Unexpected error.', $jsonResponse['error_description']);
    }

    /**
     * @test
     * @testdox Throttle request
     *
     * @return void
     */
    public function throttle(): void
    {
        $this->app->router->post('/', function () {
            throw new ThrottleRequestsException();
        });

        $this->post('/');

        $jsonResponse = $this->response->json();
        $this->assertEquals('too_many_requests', $jsonResponse['error']);
    }

    /**
     * @test
     * @testdox Token expired
     *
     * @return void
     */
    public function tokenExpired(): void
    {
        $this->app->router->post('/', function () {
            throw new TokenExpiredException();
        });

        $this->post('/');

        $jsonResponse = $this->response->json();
        $this->assertEquals('token_expired', $jsonResponse['error']);
    }

    /**
     * @test
     * @testdox Token expired
     *
     * @return void
     */
    public function unauthorized(): void
    {
        $this->app->router->post('/', function () {
            throw new UnauthorizedException();
        });

        $this->post('/');

        $jsonResponse = $this->response->json();
        $this->assertEquals('unauthorized', $jsonResponse['error']);
    }
}
