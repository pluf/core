<?php
namespace Pluf\Data\Schema;

use Pluf\Data\ModelUtils;
use Pluf\Options;
use Pluf\Data\ModelDescription;
use Pluf\Db\Expression;
use Pluf;
use WKT;
use geoPHP;

class MySQLSchema extends \Pluf\Data\Schema
{

    /**
     * Mapping of the fields.
     */
    public $mappings = array(
        self::VARCHAR => 'varchar(%s)',
        self::SEQUENCE => 'mediumint(9) unsigned not null auto_increment',
        self::BOOLEAN => 'bool',
        self::DATE => 'date',
        self::DATETIME => 'datetime',
        self::FILE => 'varchar(250)',
        self::MANY_TO_MANY => null,
        self::ONE_TO_MANY => null,
        self::MANY_TO_ONE => 'mediumint(9) unsigned',
        self::FOREIGNKEY => 'mediumint(9) unsigned',
        self::TEXT => 'longtext',
        self::HTML => 'longtext',
        self::TIME => 'time',
        self::INTEGER => 'integer',
        self::EMAIL => 'varchar(150)',
        self::PASSWORD => 'varchar(150)',
        self::FLOAT => 'numeric(%s, %s)',
        self::BLOB => 'blob',
        self::GEOMETRY => 'GEOMETRY'
    );

    public $defaults = array(
        self::VARCHAR => "''",
        self::SEQUENCE => null,
        self::SEQUENCE => 1,
        self::DATE => 0,
        self::DATETIME => 0,
        self::FILE => "''",
        self::MANY_TO_MANY => null,
        self::MANY_TO_ONE => 0,
        self::ONE_TO_MANY => null,
        self::FOREIGNKEY => 0,
        self::TEXT => "''",
        self::HTML => "''",
        self::TIME => 0,
        self::INTEGER => 0,
        self::EMAIL => "''",
        self::PASSWORD => "''",
        self::FLOAT => 0.0,
        self::BLOB => "''",
        self::GEOMETRY => null
    );

    private $con = null;

    /**
     * Creates new instance of the schema
     *
     * @param Options $options
     */
    public function __construct(?Options $options = null)
    {
        parent::__construct($options);
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
    public function createConstraintQueries(\Pluf\Data\Model $model): array
    {
        $smd = ModelDescription::getInstance($model);
        $table = $this->getTableName($smd);
        $alter_tbl = 'ALTER TABLE ' . $table;
        // $cols = $model->_a['cols'];
        $constraints = [];
        $manytomany = [];
        $manytooen = [];

        foreach ($smd as $property) {
            if ($property->type == self::MANY_TO_MANY) {
                $manytomany[] = $property;
            }
            if ($property->type == self::MANY_TO_ONE) {
                $manytooen[] = $property;
            }
        }

        // Forigne Keys
        foreach ($manytooen as $property) {
            $tmd = ModelDescription::getInstance($property->model);
            $referto = $this->getTableName($tmd);
            // Add the foreignkey constraints
            $constraints[] = $alter_tbl . ' ADD CONSTRAINT ' . $this->getShortenedFKeyName($table . '_' . $property->name . '_fkey') . '
                    FOREIGN KEY (' . $this->qn($property->name) . ')
                    REFERENCES ' . $this->getTableName($referto) . ' (id)
                    ON DELETE NO ACTION ON UPDATE NO ACTION';
        }

        // Now for the many to many
        foreach ($manytomany as $relation) {
            $tmd = ModelDescription::getInstance($relation->model);
            $table = $this->getRelationTable($smd, $tmd, $relation);
            $alter_tbl = 'ALTER TABLE ' . $table;
            $constraints[] = $alter_tbl . ' ADD CONSTRAINT ' . $this->getShortenedFKeyName($table . '_fkey1') . '
                FOREIGN KEY (' . $this->getRelationSourceField($smd, $tmd, $relation) . ')
                REFERENCES ' . $this->getTableName($smd) . ' (id)
                ON DELETE NO ACTION ON UPDATE NO ACTION';
            $constraints[] = $alter_tbl . ' ADD CONSTRAINT ' . $this->getShortenedFKeyName($table . '_fkey2') . '
                FOREIGN KEY (' . $this->getRelationTargetField($smd, $tmd, $relation) . ')
                REFERENCES ' . $this->getTableName($tmd) . ' (id)
                ON DELETE NO ACTION ON UPDATE NO ACTION';
        }
        return $constraints;
    }

    /**
     * Get the SQL to drop the constraints for the given model
     *
     * @param
     *            Object Model
     * @return array Array of SQL strings ready to execute.
     */
    public function dropConstraintQueries(\Pluf\Data\Model $model): array
    {
        $table = $this->prefix . $model->_a['table'];
        $constraints = array();
        $alter_tbl = 'ALTER TABLE ' . $table;
        $cols = $model->_a['cols'];
        $manytomany = array();

        foreach ($cols as $col => $val) {
            // remember these for later
            $type = $val['type'];
            if ($type == self::MANY_TO_MANY) {
                $manytomany[] = $col;
            }
            if ($type == self::FOREIGNKEY) {
                // Add the foreignkey constraints
                // $referto = new $val['model']();
                $constraints[] = $alter_tbl . ' DROP CONSTRAINT ' . $this->getShortenedFKeyName($table . '_' . $col . '_fkey');
            }
        }

        // Now for the many to many
        foreach ($manytomany as $many) {
            $omodel = new $cols[$many]['model']();
            $table = ModelUtils::getAssocTable($model, $omodel);
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
    function qn(string $col): string
    {
        $cols = explode(',', $col);
        // $colArray = array();
        foreach ($cols as $myCol) {
            $colsArray[] = '`' . trim($myCol) . '`';
        }
        return implode(',', $colsArray);
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Data\Schema::dropTableQueries()
     */
    public function dropTableQueries(\Pluf\Data\Model $model): array
    {
        $cols = $model->_a['cols'];
        $modelTable = $this->getTableName($model);
        $manytomany = array();
        $sql = 'DROP TABLE IF EXISTS `' . $modelTable . '`';

        foreach ($cols as $col => $val) {
            if ($val['type'] == self::MANY_TO_MANY) {
                $manytomany[] = $col;
            }
        }

        // Now for the many to many
        foreach ($manytomany as $many) {
            $omodel = new $cols[$many]['model']();
            $table = $this->getRelationTable($model, $omodel);
            $sql .= ', `' . $table . '`';
        }
        return array(
            $sql
        );
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Data\Schema::createTableQueries()
     */
    public function createTableQueries(\Pluf\Data\Model $model): array
    {
        $tables = array();
        $cols = $model->_a['cols'];
        $manytomany = array();
        $sql = 'CREATE TABLE `' . $this->prefix . $model->_a['table'] . '` (';

        foreach ($cols as $col => $val) {
            // $field = new $val['type']();
            $type = $val['type'];
            if ($type != self::MANY_TO_MANY) {
                $sql .= $this->qn($col) . ' ';
                $_tmp = $this->mappings[$type];
                if ($type == self::VARCHAR) {
                    if (isset($val['size'])) {
                        $_tmp = sprintf($this->mappings[$type], $val['size']);
                    } else {
                        $_tmp = sprintf($this->mappings[$type], '150');
                    }
                }
                if ($type == self::FLOAT) {
                    if (! isset($val['max_digits'])) {
                        $val['max_digits'] = 32;
                    }
                    if (! isset($val['decimal_places'])) {
                        $val['decimal_places'] = 8;
                    }
                    $_tmp = sprintf($this->mappings[$type], $val['max_digits'], $val['decimal_places']);
                }
                $sql .= $_tmp;
                if (empty($val['is_null'])) {
                    $sql .= ' NOT NULL';
                }
                if ($type != self::TEXT && $type != self::BLOB && $type != self::GEOMETRY) {
                    if (isset($val['default'])) {
                        $sql .= ' default ';
                        $sql .= $model->_toDb($val['default'], $col);
                    } elseif ($type != self::SEQUENCE) {
                        $sql .= ' default ' . $this->defaults[$type];
                    }
                }
                $sql .= ',';
            } else {
                $manytomany[] = $col;
            }
        }
        $sql .= ' PRIMARY KEY (`id`))';
        $engine = 'InnoDB';
        if (key_exists('engine', $model->_a)) {
            $engine = $model->_a['engine'];
        }
        $sql .= 'ENGINE=' . $engine . ' DEFAULT CHARSET=utf8;';
        $tables[$this->prefix . $model->_a['table']] = $sql;

        // Now for the many to many
        foreach ($manytomany as $many) {
            $omodel = new $cols[$many]['model']();
            $table = ModelUtils::getAssocTable($model, $omodel);

            $ra = ModelUtils::getAssocField($model);
            $rb = ModelUtils::getAssocField($omodel);

            $sql = 'CREATE TABLE `' . $table . '` (';
            $sql .= $ra . ' ' . $this->mappings[self::FOREIGNKEY] . ' default 0,';
            $sql .= $rb . ' ' . $this->mappings[self::FOREIGNKEY] . ' default 0,';
            $sql .= 'PRIMARY KEY (' . $ra . ', ' . $rb . ')';
            $sql .= ') ENGINE=InnoDB';
            $sql .= ' DEFAULT CHARSET=utf8;';
            $tables[$table] = $sql;
        }
        return $tables;
    }

    public function createIndexQueries(\Pluf\Data\Model $model): array
    {
        $index = array();
        $indexes = $model->getIndexes();
        $modelTable = $this->getTableName($model);
        foreach ($indexes as $idx => $val) {
            if (! isset($val['col'])) {
                $val['col'] = $idx;
            }
            $type = '';
            if (isset($val['type']) && strcasecmp($val['type'], 'normal') != 0) {
                $type = $val['type'];
            }
            $index[$modelTable . '_' . $idx] = sprintf('CREATE %s INDEX `%s` ON `%s` (%s);', $type, $idx, $modelTable, $this->qn($val['col']));
        }
        foreach ($model->_a['cols'] as $col => $val) {
            $type = $val['type'];
            if ($type == self::FOREIGNKEY) {
                $index[$modelTable . '_' . $col . '_foreignkey'] = sprintf('CREATE INDEX `%s` ON `%s` (`%s`);', $col . '_foreignkey_idx', $modelTable, $col);
            }
            if (isset($val['unique']) && $val['unique'] == true) {
                // Add tenant column to index if config and table are multitenant.
                $columns = (Pluf::f('multitenant', false) && $model->_a['multitenant']) ? 'tenant,' . $col : $col;
                $index[$modelTable . '_' . $col . '_unique'] = sprintf('CREATE UNIQUE INDEX `%s` ON `%s` (%s);', $col . '_unique_idx', $modelTable, $this->qn($columns));
            }
        }
        return $index;
    }

    /**
     *
     * @param Object $val
     * @return string
     */
    public static function geometryFromDb($val)
    {
        // TODO: maso, 2018: check if we need to use geoPHP::load to load data
        // SEE: https://github.com/phayes/geoPHP
        /*
         * maso, 1395: convert $val (from BLOB) to WKT
         *
         * 1- SRID
         * 2- WKB
         *
         * See:
         * https://dev.mysql.com/doc/refman/5.7/en/gis-data-formats.html#gis-internal-format
         */
        if ($val == null) {
            return null;
        }
        $data = unpack("lsrid/H*wkb", $val);
        $geometry = geoPHP::load($data['wkb'], 'wkb', TRUE);
        $wkt_writer = new WKT();
        $wkt = $wkt_writer->write($geometry);
        return $wkt;
    }

    /**
     * Convert text to geometry
     *
     * @return string
     */
    public static function geometryToDb($val, $db)
    {
        // TODO: maso, 2018: check if we need to use geoPHP::load to load data
        // SEE: https://github.com/phayes/geoPHP
        // TODO: hadi 1397-06-16: Here $val should be encoded
        // if($db->engine === 'SQLite'){
        // return (null === $val || empty($val)) ? null : "'" . $val . "'";
        // }
        if (! isset($val)) {
            return null;
        }
        return new Expression('GeometryFromText("[]")', [
            $val
        ]);
    }
}


