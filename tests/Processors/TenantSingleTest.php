<?php
namespace Pluf\Test\Processors;

require_once 'Pluf.php';

use PHPUnit\Framework\TestCase;
use Pluf\Dispatcher;
use Pluf\Module;
use Pluf\HTTP\Request;
use Pluf\Processors\TenantProcessor;
use Pluf;
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
        $config['processors'] = array(
            TenantProcessor::class
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

        $this->assertEquals(200, Dispatcher::getInstance()->setViews(Module::loadControllers())
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

        $this->assertEquals(200, Dispatcher::getInstance()->setViews(Module::loadControllers())
            ->dispatch(new Request('/helloword/HelloWord'))
            ->getStatusCode());
    }
}