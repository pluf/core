<?php
namespace Pluf\Db;

use Pluf\Options;
use Pluf;
use Pluf_Model;

/**
 * Generator of the schemas corresponding to a given model.
 *
 * This class is for SQLite, you can create a class on the same
 * model for another database engine.
 *
 * @author maso
 *        
 */
class SQLiteSchema extends Schema
{

    /**
     * Mapping of the fields.
     */
    public $mappings = array(
        Engine::VARCHAR => 'varchar(%s)',
        Engine::SEQUENCE => 'integer primary key autoincrement',
        Engine::BOOLEAN => 'bool',
        Engine::DATE => 'date',
        Engine::DATETIME => 'datetime',
        Engine::FILE => 'varchar(250)',
        Engine::MANY_TO_MANY => null,
        Engine::FOREIGNKEY => 'integer',
        Engine::TEXT => 'text',
        Engine::HTML => 'text',
        Engine::TIME => 'time',
        Engine::INTEGER => 'integer',
        Engine::EMAIL => 'varchar(150)',
        Engine::PASSWORD => 'varchar(150)',
        Engine::FLOAT => 'real',
        Engine::BLOB => 'blob',
        Engine::GEOMETRY => 'text'
    );

    public $defaults = array(
        Engine::VARCHAR => "''",
        Engine::SEQUENCE => null,
        Engine::BOOLEAN => 1,
        Engine::DATE => 0,
        Engine::DATETIME => 0,
        Engine::FILE => "''",
        Engine::MANY_TO_MANY => null,
        Engine::FOREIGNKEY => 0,
        Engine::TEXT => "''",
        Engine::HTML => "''",
        Engine::TIME => 0,
        Engine::INTEGER => 0,
        Engine::EMAIL => "''",
        Engine::PASSWORD => "''",
        Engine::FLOAT => 0.0,
        Engine::BLOB => "''",
        Engine::GEOMETRY => "''"
    );

    private $con = null;

    /**
     * Creates new instance of the schema
     *
     * @param Engine $con
     * @param Options $options
     */
    function __construct(Engine $con, ?Options $options = null)
    {
        parent::__construct($con, $options);

        // TODO: maso, 2020: load options
    }

    public function createTableQueries(Pluf_Model $model): array
    {
        $tables = array();
        $cols = $model->_a['cols'];
        $manytomany = array();

        $table = $this->getTableName($model);

        $sql_col = array();

        foreach ($cols as $col => $description) {
            $type = $description['type'];
            if ($type == Engine::MANY_TO_MANY) {
                $manytomany[] = $col;
                continue;
            }
            $sql = $this->qn($col) . ' ';
            $_tmp = $this->mappings[$type];
            if ($type == Engine::VARCHAR) {
                if (isset($description['size'])) {
                    $_tmp = sprintf($this->mappings[$type], $description['size']);
                } else {
                    $_tmp = sprintf($this->mappings[$type], '150');
                }
            }
            if ($type == Engine::FLOAT) {
                if (! isset($description['max_digits'])) {
                    $val['max_digits'] = 32;
                }
                if (! isset($val['decimal_places'])) {
                    $val['decimal_places'] = 8;
                }
                $_tmp = sprintf($this->mappings[$type], $val['max_digits'], $val['decimal_places']);
            }
            $sql .= $_tmp;
            if (empty($val['is_null'])) {
                $sql .= ' not null';
            }
            if (isset($val['default'])) {
                $sql .= ' default ' . $model->_toDb($val['default'], $col);
            } elseif ($type != ENgine::SEQUENCE) {
                $sql .= ' default ' . $this->defaults[$type];
            }
            $sql_col[] = $sql;
        }

        $tables[$table] = 'CREATE TABLE ' . $table . ' (' . implode(",", $sql_col) . ');';

        // Now for the many to many
        foreach ($manytomany as $many) {
            $omodel = new $cols[$many]['model']();
            $relationName = null; // TODO: maso, 2020: get relation name
            $table = $this->getRelationTable($model, $omodel, $relationName);

            $ra = $this->getAssocField($model, $relationName);
            $rb = $this->getAssocField($omodel, $relationName);

            $sql = 'CREATE TABLE ' . $table . ' (';
            $sql .= "\n" . $ra . $this->mappings[Engine::FOREIGNKEY] . ' default 0,';
            $sql .= "\n" . $rb . $this->mappings[Engine::FOREIGNKEY] . ' default 0,';
            $sql .= "\n" . 'primary key (' . $ra . ', ' . $rb . ')';
            $sql .= "\n" . ');';
            $tables[$table] = $sql;
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
    public function createIndexQueries(Pluf_Model $model): array
    {
        $index = array();
        $idxs = $model->getIndexes();
        $schema = $model->getEngine()->getSchema();
        $table = $schema->getTableName($model);
        foreach ($idxs as $idx => $val) {
            if (! isset($val['col'])) {
                $val['col'] = $idx;
            }
            $unique = (isset($val['type']) && ($val['type'] == 'unique')) ? 'UNIQUE ' : '';
            $index[$this->prefix . $model->_a['table'] . '_' . $idx] = sprintf('CREATE %sINDEX %s ON %s (%s);', $unique, $table . '_' . $idx, $table, $schema::quoteColumn($val['col'], $this));
        }
        foreach ($model->_a['cols'] as $col => $description) {
            // $field = new $val['type']();
            $type = $description['type'];
            if ($type == Engine::FOREIGNKEY) {
                $index[$this->prefix . $model->_a['table'] . '_' . $col . '_foreignkey'] = sprintf('CREATE INDEX %s ON %s (%s);', $table . '_' . $col . '_foreignkey_idx', $table, $schema::quoteColumn($col, $this));
            }
            if (isset($description['unique']) and $description['unique'] == true) {
                // Add tenant column to index if config and table are multitenant.
                $columns = (Pluf::f('multitenant', false) && $model->_a['multitenant']) ? 'tenant,' . $col : $col;
                $index[$this->prefix . $model->_a['table'] . '_' . $col . '_unique'] = sprintf('CREATE UNIQUE INDEX %s ON %s (%s);', $table . '_' . $col . '_unique_idx', $table, $schema::quoteColumn($columns, $this));
            }
        }
        return $index;
    }

    /**
     * Get the SQL to drop the tables corresponding to the model.
     *
     * @param
     *            Object Model
     * @return string SQL string ready to execute.
     */
    public function dropTableQueries(Pluf_Model $model): array
    {
        $cols = $model->_a['cols'];
        $manytomany = array();
        $sql = array();
        $sql[] = 'DROP TABLE IF EXISTS ' . $this->prefix . $model->_a['table'];
        foreach ($cols as $col => $description) {
            if ($description['type'] == Engine::MANY_TO_MANY) {
                $manytomany[] = $col;
            }
        }

        // Now for the many to many
        foreach ($manytomany as $many) {
            $omodel = new $cols[$many]['model']();
            $table = $this->getRelationTable($model, $omodel);
            // $table = Pluf_ModelUtils::getAssocTable($model, $omodel);
            $sql[] = 'DROP TABLE IF EXISTS ' . $table;
        }
        return $sql;
    }

    /**
     * Quote the column name.
     *
     * @param
     *            string Name of the column
     * @return string Escaped name
     */
    public function qn($col): string
    {
        return '"' . $col . '"';
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Db\Schema::createConstraintQueries()
     */
    public function createConstraintQueries(Pluf_Model $model): array
    {
        return [];
    }

    /**
     * SQLite cannot drop foreign keys from existing tables,
     * so we skip their deletion completely.
     *
     * @param
     *            Object Model
     * @return array
     */
    public function dropConstraintQueries(Pluf_Model $model): array
    {
        return [];
    }
}

