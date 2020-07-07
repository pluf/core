<?php
namespace Pluf\Test\Processors;

use PHPUnit\Framework\TestCase;
use Pluf\Dispatcher;
use Pluf\Module;
use Pluf\HTTP\Request;
use Pluf\Pluf\Tenant;
use Pluf\Processors\TenantProcessor;
use Pluf;

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
        $config['processors'] = array(
            TenantProcessor::class
        );

        // Install
        Pluf::start($config);
        $migration = new \Pluf\Migration();
        $migration->install();
    }

    /**
     *
     * @afterClass
     */
    public static function removeApplication()
    {
        // unistall
        $migration = new \Pluf\Migration();
        $migration->uninstall();
    }

    /**
     *
     * @test
     */
    public function shouldRedirectOnBadTenant()
    {
        $_SERVER['HTTP_HOST'] = 'xxx.' . rand();

        $this->assertEquals(302, Dispatcher::getInstance()->setViews(Module::loadControllers())
            ->dispatch(new Request('/helloword/HelloWord'))
            ->getStatusCode());
    }

    /**
     *
     * @test
     */
    public function shouldRedirectOnBadDomainNameTenant()
    {
        $_SERVER['HTTP_HOST'] = 'x x x.' . rand();

        $this->assertEquals(302, Dispatcher::getInstance()->setViews(Module::loadControllers())
            ->dispatch(new Request('/helloword/HelloWord'))
            ->getStatusCode());
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

        $this->assertEquals(200, Dispatcher::getInstance()->setViews(Module::loadControllers())
            ->dispatch(new Request('/helloword/HelloWord'))
            ->getStatusCode());
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

        $this->assertEquals(200, Dispatcher::getInstance()->setViews(Module::loadControllers())
            ->dispatch(new Request('/helloword/HelloWord'))
            ->getStatusCode());
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

        $this->assertEquals(302, Dispatcher::getInstance()->setViews(Module::loadControllers())
            ->dispatch(new Request('/helloword/HelloWord'))
            ->getStatusCode());

        $request = new Request('/helloword/HelloWord');
        $request->setHeader('_PX_tenant', $tenant->id);
        $this->assertEquals(200, Dispatcher::getInstance()->setViews(Module::loadControllers())
            ->dispatch($request)
            ->getStatusCode());
    }
}

