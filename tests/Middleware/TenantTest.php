<?php
namespace Pluf\Test\Middleware;

use PHPUnit\Framework\TestCase;
use Pluf;
use Pluf_Dispatcher;
use Pluf_Migration;
use Pluf_Tenant;
use Pluf\Module;
use Pluf\Middleware;

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
            '\Pluf\Middleware\Tenant'
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

        $dispatcher = new Pluf_Dispatcher();
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

        $dispatcher = new Pluf_Dispatcher();
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

        $tenant = new Pluf_Tenant();
        $tenant->domain = $_SERVER['HTTP_HOST'];
        $tenant->subdomain = 'yyy' . rand();
        $tenant->create();
        $this->assertFalse($tenant->isAnonymous());

        $dispatcher = new Pluf_Dispatcher();
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
        $sub = 'xxx' . rand();
        $_SERVER['HTTP_HOST'] = $sub . '.' . rand();

        $tenant = new Pluf_Tenant();
        $tenant->domain = $_SERVER['HTTP_HOST'];
        $tenant->subdomain = $sub;
        $tenant->create();
        $this->assertFalse($tenant->isAnonymous());

        $dispatcher = new Pluf_Dispatcher();
        $results = $dispatcher->dispatch('/helloword/HelloWord', Module::loadControllers());

        $request = $results[0];
        $response = $results[1];

        $this->assertEquals($response->status_code, 200);

        /*
         * Direct
         */
        $md = new Middleware\Tenant();
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

        $tenant = new Pluf_Tenant();
        $tenant->domain = rand();
        $tenant->subdomain = rand();
        $tenant->create();
        $this->assertFalse($tenant->isAnonymous());

        $dispatcher = new Pluf_Dispatcher();
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
        $md = new Middleware\Tenant();
        $response = $md->process_request($request);
        $this->assertFalse($response);
    }
}

