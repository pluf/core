<?php
namespace Pluf\Test\Middleware;

require_once 'Pluf.php';

use PHPUnit\Framework\TestCase;
use Pluf\Dispatcher;
use Pluf\Module;
use Pluf\Middleware\TenantMiddleware;
use Pluf\Pluf\Tenant;
use Pluf;
use Pluf_Migration;

class TenantTest extends TestCase
{

    /**
     *
     * @beforeClass
     */
    public static function initMutlitenantApplication()
    {
        // Load config
        $config = include __DIR__ . '/../conf/config.php';
        $config['multitenant'] = true;
        $config['middleware_classes'] = array(
            '\Pluf\Middleware\TenantMiddleware'
        );

        // Install
        Pluf::start($config);
        $migration = new Pluf_Migration();
        $migration->install();
    }

    /**
     *
     * @afterClass
     */
    public static function removeApplication()
    {
        // unistall
        $migration = new Pluf_Migration();
        $migration->uninstall();
    }

    /**
     *
     * @test
     */
    public function shouldRedirectOnBadTenant()
    {
        $_SERVER['HTTP_HOST'] = 'xxx.' . rand();

        $dispatcher = new Dispatcher();
        $results = $dispatcher->dispatch('/helloword/HelloWord');

        // $request = $results[0];
        $response = $results[1];

        $this->assertEquals($response->status_code, 302);
    }

    /**
     *
     * @test
     */
    public function shouldRedirectOnBadDomainNameTenant()
    {
        $_SERVER['HTTP_HOST'] = 'x x x.' . rand();

        $dispatcher = new Dispatcher();
        $results = $dispatcher->dispatch('/helloword/HelloWord');

        // $request = $results[0];
        $response = $results[1];

        $this->assertEquals($response->status_code, 302);
    }

    /**
     *
     * @test
     */
    public function shouldFindTenantBasedDomain()
    {
        $_SERVER['HTTP_HOST'] = 'xxx.' . rand();

        $tenant = new Tenant();
        $tenant->domain = $_SERVER['HTTP_HOST'];
        $tenant->subdomain = 'yyy' . rand();
        $tenant->create();
        $this->assertFalse($tenant->isAnonymous());

        $dispatcher = new Dispatcher();
        $results = $dispatcher->dispatch('/helloword/HelloWord', Module::loadControllers());

        // $request = $results[0];
        $response = $results[1];

        $this->assertEquals($response->status_code, 200);
    }

    /**
     *
     * @test
     */
    public function shouldFindTenantBasedSubdomain()
    {
        /*
         * From Dispatcher
         */
        $sub = rand() . 'xxx' . rand();
        $_SERVER['HTTP_HOST'] = $sub . '.' . rand();

        $tenant = new Tenant();
        $tenant->domain = $_SERVER['HTTP_HOST'];
        $tenant->subdomain = $sub;
        $tenant->create();
        $this->assertFalse($tenant->isAnonymous());

        $dispatcher = new Dispatcher();
        $results = $dispatcher->dispatch('/helloword/HelloWord', Module::loadControllers());

        $request = $results[0];
        $response = $results[1];

        $this->assertEquals($response->status_code, 200);

        /*
         * Direct
         */
        $md = new TenantMiddleware();
        $response = $md->process_request($request);
        $this->assertFalse($response);
    }

    /**
     *
     * @test
     */
    public function shouldFindTenantFromHeader()
    {
        /*
         * From Dispatcher
         */
        $_SERVER['HTTP_HOST'] = 'xxx.' . rand();

        $tenant = new Tenant();
        $tenant->domain = 'xxxxsssaa' . rand();
        $tenant->subdomain = 'wweerr' . rand();
        $tenant->create();
        $this->assertFalse($tenant->isAnonymous());

        $dispatcher = new Dispatcher();
        $results = $dispatcher->dispatch('/helloword/HelloWord', Module::loadControllers());

        $request = $results[0];
        $response = $results[1];

        $this->assertEquals($response->status_code, 302);

        /*
         * Direct
         */
        $request->HEADERS = array(
            '_PX_tenant' => $tenant->id
        );
        $md = new TenantMiddleware();
        $response = $md->process_request($request);
        $this->assertFalse($response);
    }
}

