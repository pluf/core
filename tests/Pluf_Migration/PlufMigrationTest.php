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

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\IncompleteTestError;

require_once 'Pluf.php';

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class PlufMigrationTest extends TestCase
{

    /**
     * @before
     */
    protected function setUpTest()
    {
        Pluf::start(__DIR__ . '/../conf/config.php');
    }

    public function testMigrate()
    {
        $m = new Pluf_Migration();
        $m->dry_run = false;
        $this->assertEquals(true, $m->migrate());
        $this->assertEquals(true, $m->migrate(0));
    }

    public function testMigrateDown()
    {
        $m = new Pluf_Migration();
        $m->dry_run = true;
        $this->assertEquals(true, $m->migrate(0));
    }

    public function testMigrateUp()
    {
        $m = new Pluf_Migration();
        $m->dry_run = true;
        $this->assertEquals(true, $m->migrate(5));
    }
}
