<?php
namespace Pluf\Test\Middleware;

use PHPUnit\Framework\TestCase;
use Pluf\Module;
use Pluf;
use Pluf_Dispatcher;
use Pluf_Migration;

class TenantSingleTest extends TestCase
{

    /**
     *
     * @beforeClass
     */
    public static function initMutlitenantApplication()
    {
        // Load config
        $config = include __DIR__ . '/../conf/config.php';
        $config['multitenant'] = false;
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
        $results = $dispatcher->dispatch('/helloword/HelloWord', Module::loadControllers());

        // $request = $results[0];
        $response = $results[1];

        $this->assertEquals($response->status_code, 200);
    }

    /**
     *
     * @test
     */
    public function shouldRedirectOnBadDomainNameTenant()
    {
        $_SERVER['HTTP_HOST'] = 'x x x.' . rand();

        $dispatcher = new Pluf_Dispatcher();
        $results = $dispatcher->dispatch('/helloword/HelloWord', Module::loadControllers());

        // $request = $results[0];
        $response = $results[1];

        $this->assertEquals($response->status_code, 200);
    }
}