<?php
namespace Pluf\Db;

use Pluf_Model;
use Pluf_ModelUtils;

/**
 *
 * Generator of the schemas corresponding to a given model.
 *
 * This class is for PostgreSQL, you can create a class on the same
 * model for another database engine.
 *
 * @author maso
 *        
 */
class PostgreSQLSchema extends schema
{

    /**
     * Mapping of the fields.
     */
    public $mappings = array(
        Engine::VARCHAR => 'character varying',
        Engine::SEQUENCE => 'serial',
        Engine::BOOLEAN => 'boolean',
        Engine::DATE => 'date',
        Engine::DATETIME => 'timestamp',
        Engine::FILE => 'character varying',
        Engine::MANY_TO_MANY => null,
        Engine::FOREIGNKEY => 'integer',
        Engine::TEXT => 'text',
        Engine::HTML => 'text',
        Engine::TIME => 'time',
        Engine::INTEGER => 'integer',
        Engine::EMAIL => 'character varying',
        Engine::PASSWORD => 'character varying',
        Engine::FLOAT => 'real',
        Engine::BLOB => 'bytea'
    );

    public $defaults = array(
        Engine::VARCHAR => "''",
        Engine::SEQUENCE => null,
        Engine::BOOLEAN => 'FALSE',
        Engine::DATE => "'0001-01-01'",
        Engine::DATETIME => "'0001-01-01 00:00:00'",
        Engine::FILE => "''",
        Engine::MANY_TO_MANY => null,
        Engine::FOREIGNKEY => 0,
        Engine::TEXT => "''",
        Engine::HTMLL => "''",
        Engine::TIME => "'00:00:00'",
        Engine::DATETIME => 0,
        Engine::EMAIL => "''",
        Engine::PASSWORD => "''",
        Engine::FLOAT => 0.0,
        Engine::BLOB => "''"
    );

    private $con = null;

    function __construct($con)
    {
        $this->con = $con;
    }

    /**
     * Get the SQL to generate the tables of the given model.
     *
     * @param
     *            Object Model
     * @return array Array of SQL strings ready to execute.
     */
    function getSqlCreate($model)
    {
        $tables = array();
        $cols = $model->_a['cols'];
        $manytomany = array();
        $query = 'CREATE TABLE ' . $this->con->pfx . $model->_a['table'] . ' (';
        $sql_col = array();
        foreach ($cols as $col => $val) {
            $field = new $val['type']();
            if ($field->type != 'manytomany') {
                $sql = $this->con->qn($col) . ' ';
                $sql .= $this->mappings[$field->type];
                if (empty($val['is_null'])) {
                    $sql .= ' NOT NULL';
                }
                if (isset($val['default'])) {
                    $sql .= ' default ';
                    $sql .= $model->_toDb($val['default'], $col);
                } elseif ($field->type != 'sequence') {
                    $sql .= ' default ' . $this->defaults[$field->type];
                }
                $sql_col[] = $sql;
            } else {
                $manytomany[] = $col;
            }
        }
        $sql_col[] = 'CONSTRAINT ' . $this->con->pfx . $model->_a['table'] . '_pkey PRIMARY KEY (id)';
        $query = $query . "\n" . implode(",\n", $sql_col) . "\n" . ');';
        $tables[$this->con->pfx . $model->_a['table']] = $query;
        // Now for the many to many
        // FIXME add index on the second column
        foreach ($manytomany as $many) {
            $omodel = new $cols[$many]['model']();
            $table = Pluf_ModelUtils::getAssocTable($model, $omodel);

            $ra = Pluf_ModelUtils::getAssocField($model);
            $rb = Pluf_ModelUtils::getAssocField($omodel);

            $sql = 'CREATE TABLE ' . $table . ' (';
            $sql .= "\n" . $ra . ' ' . $this->mappings[Engine::FOREIGNKEY] . ' default 0,';
            $sql .= "\n" . $rb . ' ' . $this->mappings[Engine::FOREIGNKEY] . ' default 0,';
            $sql .= "\n" . 'CONSTRAINT ' . $this->getShortenedIdentifierName($this->con->pfx . $table . '_pkey') . ' PRIMARY KEY (' . $ra . ', ' . $rb . ')';
            $sql .= "\n" . ');';
            $tables[$this->con->pfx . $table] = $sql;
        }
        return $tables;
    }

    /**
     * Get the SQL to generate the indexes of the given model.
     *
     * @param
     *            Object Model
     * @return array Array of SQL strings ready to execute.
     */
    function getSqlIndexes($model)
    {
        $index = array();
        foreach ($model->_a['idx'] as $idx => $val) {
            if (! isset($val['col'])) {
                $val['col'] = $idx;
            }
            if ($val['type'] == 'unique') {
                $unique = 'UNIQUE ';
            } else {
                $unique = '';
            }

            $index[$this->con->pfx . $model->_a['table'] . '_' . $idx] = sprintf('CREATE ' . $unique . 'INDEX %s ON %s (%s);', $this->con->pfx . $model->_a['table'] . '_' . $idx, $this->con->pfx . $model->_a['table'], self::quoteColumn($val['col'], $this->con));
        }
        foreach ($model->_a['cols'] as $col => $val) {
            if (isset($val['unique']) and $val['unique'] == true) {
                $index[$this->con->pfx . $model->_a['table'] . '_' . $col . '_unique'] = sprintf('CREATE UNIQUE INDEX %s ON %s (%s);', $this->con->pfx . $model->_a['table'] . '_' . $col . '_unique_idx', $this->con->pfx . $model->_a['table'], self::quoteColumn($col, $this->con));
            }
        }
        return $index;
    }

    /**
     * All identifiers in Postgres must not exceed 64 characters in length.
     *
     * @param
     *            string
     * @return string
     */
    function getShortenedIdentifierName($name)
    {
        if (strlen($name) <= 64) {
            return $name;
        }
        return substr($name, 0, 55) . '_' . substr(md5($name), 0, 8);
    }

    /**
     * Get the SQL to create the constraints for the given model
     *
     * @param
     *            Object Model
     * @return array Array of SQL strings ready to execute.
     */
    function getSqlCreateConstraints($model)
    {
        $table = $this->con->pfx . $model->_a['table'];
        $constraints = array();
        $alter_tbl = 'ALTER TABLE ' . $table;
        $cols = $model->_a['cols'];
        $manytomany = array();

        foreach ($cols as $col => $val) {
            $field = new $val['type']();
            // remember these for later
            if ($field->type == 'manytomany') {
                $manytomany[] = $col;
            }
            if ($field->type == Engine::FOREIGNKEY) {
                // Add the foreignkey constraints
                $referto = new $val['model']();
                $constraints[] = $alter_tbl . ' ADD CONSTRAINT ' . $this->getShortenedIdentifierName($table . '_' . $col . '_fkey') . '
                    FOREIGN KEY (' . $this->con->qn($col) . ')
                    REFERENCES ' . $this->con->pfx . $referto->_a['table'] . ' (id) MATCH SIMPLE
                    ON UPDATE NO ACTION ON DELETE NO ACTION';
            }
        }

        // Now for the many to many
        foreach ($manytomany as $many) {
            $omodel = new $cols[$many]['model']();
            $table = Pluf_ModelUtils::getAssocTable($model, $omodel);

            $alter_tbl = 'ALTER TABLE ' . $table;
            $constraints[] = $alter_tbl . ' ADD CONSTRAINT ' . $this->getShortenedIdentifierName($table . '_fkey1') . '
                FOREIGN KEY (' . strtolower($model->_a['model']) . '_id)
                REFERENCES ' . $this->con->pfx . $model->_a['table'] . ' (id) MATCH SIMPLE
                ON UPDATE NO ACTION ON DELETE NO ACTION';
            $constraints[] = $alter_tbl . ' ADD CONSTRAINT ' . $this->getShortenedIdentifierName($table . '_fkey2') . '
                FOREIGN KEY (' . strtolower($omodel->_a['model']) . '_id)
                REFERENCES ' . $this->con->pfx . $omodel->_a['table'] . ' (id) MATCH SIMPLE
                ON UPDATE NO ACTION ON DELETE NO ACTION';
        }
        return $constraints;
    }

    /**
     * Get the SQL to drop the tables corresponding to the model.
     *
     * @param
     *            Object Model
     * @return string SQL string ready to execute.
     */
    function getSqlDelete($model)
    {
        $cols = $model->_a['cols'];
        $manytomany = array();
        $sql = array();
        $sql[] = 'DROP TABLE IF EXISTS ' . $this->con->pfx . $model->_a['table'] . ' CASCADE';
        foreach ($cols as $col => $val) {
            $field = new $val['type']();
            if ($field->type == 'manytomany') {
                $manytomany[] = $col;
            }
        }

        // Now for the many to many
        foreach ($manytomany as $many) {
            $omodel = new $cols[$many]['model']();
            $table = Pluf_ModelUtils::getAssocTable($model, $omodel);
            $sql[] = 'DROP TABLE IF EXISTS ' . $table . ' CASCADE';
        }
        return $sql;
    }

    /**
     * Get the SQL to drop the constraints for the given model
     *
     * @param
     *            Object Model
     * @return array Array of SQL strings ready to execute.
     */
    function getSqlDeleteConstraints($model)
    {
        $table = $this->con->pfx . $model->_a['table'];
        $constraints = array();
        $alter_tbl = 'ALTER TABLE ' . $table;
        $cols = $model->_a['cols'];
        $manytomany = array();

        foreach ($cols as $col => $val) {
            $field = new $val['type']();
            // remember these for later
            if ($field->type == 'manytomany') {
                $manytomany[] = $col;
            }
            if ($field->type == Engine::FOREIGNKEY) {
                // Add the foreignkey constraints
//                 $referto = new $val['model']();
                $constraints[] = $alter_tbl . ' DROP CONSTRAINT ' . $this->getShortenedIdentifierName($table . '_' . $col . '_fkey');
            }
        }

        // Now for the many to many
        foreach ($manytomany as $many) {
            $omodel = new $cols[$many]['model']();
            $table = Pluf_ModelUtils::getAssocTable($model, $omodel);
            $alter_tbl = 'ALTER TABLE ' . $table;
            $constraints[] = $alter_tbl . ' DROP CONSTRAINT ' . $this->getShortenedIdentifierName($table . '_fkey1');
            $constraints[] = $alter_tbl . ' DROP CONSTRAINT ' . $this->getShortenedIdentifierName($table . '_fkey2');
        }
        return $constraints;
    }

    /**
     * Quote the column name.
     *
     * @param
     *            string Name of the column
     * @return string Escaped name
     */
    function qn($col)
    {
        return '"' . $col . '"';
    }

    public function dropTableQueries(Pluf_Model $model): array
    {}

    public function createConstraintQueries(Pluf_Model $model): array
    {}

    public function dropConstraintQueries(Pluf_Model $model): array
    {}

    public function createTableQueries(Pluf_Model $model): array
    {}

    public function createIndexQueries(Pluf_Model $model): array
    {}
}

