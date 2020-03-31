<?php
namespace Pluf\Db;

use Pluf;
use Pluf_DB_Schema;
use Pluf_Model;
use Pluf_ModelUtils;

class MySQLSchema extends Schema
{

    /**
     * Mapping of the fields.
     */
    public $mappings = array(
        'varchar' => 'varchar(%s)',
        'sequence' => 'mediumint(9) unsigned not null auto_increment',
        'boolean' => 'bool',
        'date' => 'date',
        'datetime' => 'datetime',
        Engine::FILE => 'varchar(250)',
        Engine::MANY_TO_MANY => null,
        Engine::FOREIGNKEY => 'mediumint(9) unsigned',
        'text' => 'longtext',
        'html' => 'longtext',
        'time' => 'time',
        'integer' => 'integer',
        'email' => 'varchar(150)',
        'password' => 'varchar(150)',
        'float' => 'numeric(%s, %s)',
        'blob' => 'blob',
        // GEO types
        'point' => 'POINT',
        'geometry' => 'GEOMETRY',
        'polygon' => 'POLYGON'
    );

    public $defaults = array(
        Engine::VARCHAR => "''",
        Engine::SEQUENCE => null,
        Engine::BOOLEAN => 1,
        Engine::DATE => 0,
        Engine::DATETIME => 0,
        Engine::FILE => "''",
        Engine::MANY_TO_MANY=> null,
        Engine::FOREIGNKEY => 0,
        'text' => "''",
        'html' => "''",
        'time' => 0,
        'integer' => 0,
        'email' => "''",
        'password' => "''",
        'float' => 0.0,
        'blob' => "''",

        // GEO types
        'point' => null,
        'geometry' => null,
        'polygon' => null
    );

    private $con = null;

    /**
     * Creates new instance
     *
     * @param Object $con
     */
    function __construct($con)
    {
        $this->con = $con;
    }

    /**
     * Creates sql for DB
     *
     * @param Pluf_Model $model
     * @return string[]
     */
    function getSqlCreate(Pluf_Model $model)
    {
        $tables = array();
        $cols = $model->_a['cols'];
        $manytomany = array();
        $sql = 'CREATE TABLE `' . $this->prefix . $model->_a['table'] . '` (';

        foreach ($cols as $col => $val) {
            $field = new $val['type']();
            if ($field->type != 'manytomany') {
                $sql .= "\n" . $this->qn($col) . ' ';
                $_tmp = $this->mappings[$field->type];
                if ($field->type == 'varchar') {
                    if (isset($val['size'])) {
                        $_tmp = sprintf($this->mappings['varchar'], $val['size']);
                    } else {
                        $_tmp = sprintf($this->mappings['varchar'], '150');
                    }
                }
                if ($field->type == 'float') {
                    if (! isset($val['max_digits'])) {
                        $val['max_digits'] = 32;
                    }
                    if (! isset($val['decimal_places'])) {
                        $val['decimal_places'] = 8;
                    }
                    $_tmp = sprintf($this->mappings['float'], $val['max_digits'], $val['decimal_places']);
                }
                $sql .= $_tmp;
                if (empty($val['is_null'])) {
                    $sql .= ' NOT NULL';
                }
                if ($field->type != 'text' && $field->type != 'blob' && $field->type != 'geometry' && $field->type != 'polygon') {
                    if (isset($val['default'])) {
                        $sql .= ' default ';
                        $sql .= $model->_toDb($val['default'], $col);
                    } elseif ($field->type != 'sequence' && $field->type != 'point') {
                        $sql .= ' default ' . $this->defaults[$field->type];
                    }
                }
                $sql .= ',';
            } else {
                $manytomany[] = $col;
            }
        }
        $sql .= "\n" . 'PRIMARY KEY (`id`))';
        $engine = 'InnoDB';
        if (key_exists('engine', $model->_a)) {
            $engine = $model->_a['engine'];
        }
        $sql .= 'ENGINE=' . $engine . ' DEFAULT CHARSET=utf8;';
        $tables[$this->prefix . $model->_a['table']] = $sql;

        // Now for the many to many
        foreach ($manytomany as $many) {
            $omodel = new $cols[$many]['model']();
            $table = Pluf_ModelUtils::getAssocTable($model, $omodel);

            $ra = Pluf_ModelUtils::getAssocField($model);
            $rb = Pluf_ModelUtils::getAssocField($omodel);

            $sql = 'CREATE TABLE `' . $table . '` (';
            $sql .= "\n" . $ra . ' ' . $this->mappings[Engine::FOREIGNKEY] . ' default 0,';
            $sql .= "\n" . $rb . ' ' . $this->mappings[Engine::FOREIGNKEY] . ' default 0,';
            $sql .= "\n" . 'PRIMARY KEY (' . $ra . ', ' . $rb . ')';
            $sql .= "\n" . ') ENGINE=InnoDB';
            $sql .= ' DEFAULT CHARSET=utf8;';
            $tables[$table] = $sql;
        }
        return $tables;
    }

    /**
     * دستور معادل برای ایجاد اندیس‌ها را تولید می‌کند.
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
            $type = '';
            if (isset($val['type']) && strcasecmp($val['type'], 'normal') != 0) {
                $type = $val['type'];
            }
            $index[$this->prefix . $model->_a['table'] . '_' . $idx] = sprintf('CREATE %s INDEX `%s` ON `%s` (%s);', $type, $idx, $this->prefix . $model->_a['table'], Pluf_DB_Schema::quoteColumn($val['col'], $this->con));
        }
        foreach ($model->_a['cols'] as $col => $val) {
            $field = new $val['type']();
            if ($field->type == Engine::FOREIGNKEY) {
                $index[$this->prefix . $model->_a['table'] . '_' . $col . '_foreignkey'] = sprintf('CREATE INDEX `%s` ON `%s` (`%s`);', $col . '_foreignkey_idx', $this->prefix . $model->_a['table'], $col);
            }
            if (isset($val['unique']) and $val['unique'] == true) {
                // Add tenant column to index if config and table are multitenant.
                $columns = (Pluf::f('multitenant', false) && $model->_a['multitenant']) ? 'tenant,' . $col : $col;
                $index[$this->prefix . $model->_a['table'] . '_' . $col . '_unique'] = sprintf('CREATE UNIQUE INDEX `%s` ON `%s` (%s);', $col . '_unique_idx', $this->prefix . $model->_a['table'], Pluf_DB_Schema::quoteColumn($columns, $this->con));
            }
        }
        return $index;
    }

    /**
     * Workaround for <http://bugs.mysql.com/bug.php?id=13942> which limits the
     * length of foreign key identifiers to 64 characters.
     *
     * @param
     *            string
     * @return string
     */
    function getShortenedFKeyName($name)
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
        $table = $this->prefix . $model->_a['table'];
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
                $constraints[] = $alter_tbl . ' ADD CONSTRAINT ' . $this->getShortenedFKeyName($table . '_' . $col . '_fkey') . '
                    FOREIGN KEY (' . $this->qn($col) . ')
                    REFERENCES ' . $this->prefix . $referto->_a['table'] . ' (id)
                    ON DELETE NO ACTION ON UPDATE NO ACTION';
            }
        }

        // Now for the many to many
        foreach ($manytomany as $many) {
            $omodel = new $cols[$many]['model']();
            $table = Pluf_ModelUtils::getAssocTable($model, $omodel);

            $alter_tbl = 'ALTER TABLE ' . $table;
            $constraints[] = $alter_tbl . ' ADD CONSTRAINT ' . $this->getShortenedFKeyName($table . '_fkey1') . '
                FOREIGN KEY (' . strtolower($model->_a['model']) . '_id)
                REFERENCES ' . $this->prefix . $model->_a['table'] . ' (id)
                ON DELETE NO ACTION ON UPDATE NO ACTION';
            $constraints[] = $alter_tbl . ' ADD CONSTRAINT ' . $this->getShortenedFKeyName($table . '_fkey2') . '
                FOREIGN KEY (' . strtolower($omodel->_a['model']) . '_id)
                REFERENCES ' . $this->prefix . $omodel->_a['table'] . ' (id)
                ON DELETE NO ACTION ON UPDATE NO ACTION';
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
        $sql = 'DROP TABLE IF EXISTS `' . $this->prefix . $model->_a['table'] . '`';

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
            $sql .= ', `' . $table . '`';
        }
        return array(
            $sql
        );
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
        $table = $this->prefix . $model->_a['table'];
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
                // $referto = new $val['model']();
                $constraints[] = $alter_tbl . ' DROP CONSTRAINT ' . $this->getShortenedFKeyName($table . '_' . $col . '_fkey');
            }
        }

        // Now for the many to many
        foreach ($manytomany as $many) {
            $omodel = new $cols[$many]['model']();
            $table = Pluf_ModelUtils::getAssocTable($model, $omodel);
            $alter_tbl = 'ALTER TABLE ' . $table;
            $constraints[] = $alter_tbl . ' DROP CONSTRAINT ' . $this->getShortenedFKeyName($table . '_fkey1');
            $constraints[] = $alter_tbl . ' DROP CONSTRAINT ' . $this->getShortenedFKeyName($table . '_fkey2');
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
        return '`' . $col . '`';
    }
}
