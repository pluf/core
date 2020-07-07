<?php
namespace Pluf\Db\Query;

use Pluf\Db\Expression;

/**
 * Perform query operation on SQLite server.
 *
 * @license MIT
 * @copyright Agile Toolkit (c) http://agiletoolkit.org/
 */
class SQLite extends \Pluf\Db\Query
{

    /**
     * SQLite specific TRUNCATE template.
     *
     * @var string
     */
    protected $template_truncate = 'delete [from] [table_noalias]';

    /**
     * Returns a query for a function, which can be used as part of the GROUP
     * query which would concatenate all matching fields.
     *
     * MySQL, SQLite - group_concat
     * PostgreSQL - string_agg
     * Oracle - listagg
     *
     * @param mixed $field
     * @param string $delimiter
     *
     * @return Expression
     */
    public function groupConcat($field, $delimeter = ','): Expression
    {
        return $this->expr('group_concat({}, [])', [
            $field,
            $delimeter
        ]);
    }
}
