<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace Pluf\PlufTest\Template\Compiler;

use PHPUnit\Framework\TestCase;
use Pluf\Bootstrap;
use Pluf\Template;
use Pluf\Tenant;

class TemplateTest extends TestCase
{

    /**
     *
     * @before
     * @return void
     */
    protected function setUpTest()
    {
        Bootstrap::start(__DIR__ . '/../../conf/config.php');
    }

    public function testId()
    {
        $folders = array(
            dirname(__FILE__)
        );
        $tmpl = new Template('tpl-id.html', $folders);
        $this->assertEquals("0", $tmpl->render());
    }

    public function testTitle()
    {
        $tenant = Tenant::current();
        $folders = array(
            dirname(__FILE__)
        );
        $tmpl = new Template('tpl-title.html', $folders);
        $this->assertEquals($tenant->title, $tmpl->render());
    }

    public function testDomain()
    {
        $tenant = Tenant::current();
        $folders = array(
            dirname(__FILE__)
        );
        $tmpl = new Template('tpl-domain.html', $folders);
        $this->assertEquals($tenant->domain, $tmpl->render());
    }
}
