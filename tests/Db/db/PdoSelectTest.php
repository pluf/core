<?php
namespace Pluf\Test\Db\db;

use Pluf\Db\Connection;

class PdoSelectTest extends SelectTest
{

    /**
     *
     * @before
     */
    public function construct()
    {
        $this->c = Connection::connect(new \PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']));
        $this->pdo = $this->c->connection();

        $this->pdo->query('CREATE TEMPORARY TABLE employee (id int not null, name text, surname text, retired bool, PRIMARY KEY (id))');
    }
}
