<?php
namespace Pluf\Db\Expression;

/**
 * Perform query operation on MySQL server.
 */
class MySQL extends \Pluf\Db\Expression
{

    /**
     * Field, table and alias name escaping symbol.
     * By SQL Standard it's double quote, but MySQL uses backtick.
     *
     * @var string
     */
    protected $escape_char = '`';
}
