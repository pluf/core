<?php
namespace Pluf\Db;

/**
 */
interface Expressionable
{

    public function getDSQLExpression($expression);
}
