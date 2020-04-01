<?php

namespace Pluf\Db\Connection;

use Pluf\Db\Query;

/**
 * Custom Connection class specifically for Oracle 12c database.
 *
 * @license MIT
 * @copyright Agile Toolkit (c) http://agiletoolkit.org/
 */
class Oracle12 extends Oracle
{

    /** @var string Query classname */
    protected $query_class = Query\Oracle12c::class;
}
