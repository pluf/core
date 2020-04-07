<?php
namespace Pluf\Data;

use Pluf\Options;
use Pluf\Db\Connection;
use Pluf\Db\Expression;
use Pluf_Utils;

/**
 * Create the schema of a given Pluf_Model for a given database.
 *
 * @author maso
 *        
 */
abstract class Schema
{

    use \Pluf\DiContainerTrait;

    public const LEFT_JOIN = 'left';

    public const INNER_JOIN = 'inner';

    /**
     * Relations types
     * {
     */
    public const MANY_TO_MANY = 'Manytomany';

    public const ONE_TO_MANY = 'Onetomany';

    public const MANY_TO_ONE = 'Foreignkey';

    public const FOREIGNKEY = 'Foreignkey';

    /**
     * }
     */
    public const BOOLEAN = 'Boolean';

    public const DATE = 'Date';

    public const DATETIME = 'Datetime';

    public const EMAIL = 'Email';

    public const FILE = 'File';

    public const FLOAT = 'Float';

    public const INTEGER = 'Integer';

    public const PASSWORD = 'Password';

    public const SEQUENCE = 'Sequence';

    public const SLUG = 'Slug';

    public const TEXT = 'Text';

    public const TIME = 'Time';

    public const VARCHAR = 'Varchar';

    public const SERIALIZED = 'Serialized';

    public const COMPRESSED = 'Compressed';

    public const GEOMETRY = 'Geometry';

    public const HTML = 'Html';

    public const BLOB = 'Blob';

    /**
     * Used by the model to convert the values from and to the
     * database.
     *
     * Foreach field type you need to provide an array with 2 functions,
     * the from_db, the to_db.
     *
     * $value = from_db($value);
     * $escaped_value = to_db($value, $dbobject);
     *
     * $escaped_value is ready to be put in the SQL, that is if this is a
     * string, the value is quoted and escaped for example with SQLite:
     * 'my string'' is escaped' or with MySQL 'my string\' is escaped' the
     * starting ' and ending ' are included!
     */
    public $type_cast = array(
        /*
         * Old model
         */
        self::BOOLEAN => array(
            '\Pluf\Data\Schema::booleanFromDb',
            '\Pluf\Data\Schema::booleanToDb'
        ),
        self::DATE => array(
            '\Pluf\Data\Schema::identityFromDb',
            '\Pluf\Data\Schema::identityToDb'
        ),
        self::DATETIME => array(
            '\Pluf\Data\Schema::identityFromDb',
            '\Pluf\Data\Schema::identityToDb'
        ),
        self::EMAIL => array(
            '\Pluf\Data\Schema::identityFromDb',
            '\Pluf\Data\Schema::identityToDb'
        ),
        self::FILE => array(
            '\Pluf\Data\Schema::identityFromDb',
            '\Pluf\Data\Schema::identityToDb'
        ),
        self::FLOAT => array(
            '\Pluf\Data\Schema::floatFromDb',
            '\Pluf\Data\Schema::floatToDb'
        ),
        self::FOREIGNKEY => array(
            '\Pluf\Data\Schema::integerFromDb',
            '\Pluf\Data\Schema::integerToDb'
        ),
        self::INTEGER => array(
            '\Pluf\Data\Schema::integerFromDb',
            '\Pluf\Data\Schema::integerToDb'
        ),
        self::PASSWORD => array(
            '\Pluf\Data\Schema::identityFromDb',
            '\Pluf\Data\Schema::passwordToDb'
        ),
        self::SEQUENCE => array(
            '\Pluf\Data\Schema::integerFromDb',
            '\Pluf\Data\Schema::integerToDb'
        ),
        self::SLUG => array(
            '\Pluf\Data\Schema::identityFromDb',
            '\Pluf\Data\Schema::slugToDb'
        ),
        self::TEXT => array(
            '\Pluf\Data\Schema::identityFromDb',
            '\Pluf\Data\Schema::identityToDb'
        ),
        self::VARCHAR => array(
            '\Pluf\Data\Schema::identityFromDb',
            '\Pluf\Data\Schema::identityToDb'
        ),
        self::SERIALIZED => array(
            '\Pluf\Data\Schema::serializedFromDb',
            '\Pluf\Data\Schema::serializedToDb'
        ),
        self::COMPRESSED => array(
            '\Pluf\Data\Schema::compressedFromDb',
            '\Pluf\Data\Schema::compressedToDb'
        ),
        self::GEOMETRY => array(
            '\Pluf\Data\Schema::geometryFromDb',
            '\Pluf\Data\Schema::geometryToDb'
        )
    );

    protected string $prefix = '';

    function __construct(?Options $options = null)
    {
        $this->setDefaults($options);
    }

    /**
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     *
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * Create the tables and indexes for the current model.
     *
     * If the model is a mapped model ($model->_a['mapped'] == true) then only tables for its
     * many to many relations will be created and table for the model will not be created.
     *
     * A mapped model is a model which have not a separate table. In other word, a mapped model is
     * a specific view to another model and is not a real model.
     *
     * A mapped model may defines some new many to many relations which was not defined in the main model.
     *
     * @return mixed True if success or database error.
     */
    public function createTables(Connection $connection, ModelDescription $model): bool
    {
        $sql = $this->createTableQueries($model);
        // Note: hadi, 2019: If model is a mapped model, its table is created or will be created by a none mapped model.
        if ($model->isMapped()) {
            $modelTableName = $this->getTableName($model);
            // remove sql to create main table
            $sql = array_diff_key($sql, array(
                $modelTableName => ''
            ));
        }

        foreach ($sql as $query) {
            $connection->expr($query)->execute();
        }

        if (! $model->isMapped()) {
            $sql = $this->createIndexQueries($model);
            foreach ($sql as $query) {
                $connection->expr($query)->execute();
            }
        }
        return true;
    }

    /**
     * Drop the tables and indexes for the current model.
     *
     * @return mixed True if success or database error.
     */
    public function dropTables(Connection $connection, ModelDescription $model): bool
    {
        $sql = $this->dropTableQueries($model);
        // Note: hadi, 2019: If model is a mapped model, its table is created or will be created by a none mapped model.
        if ($model->isMapped()) {
            $modelTableName = $this->getTableName($model);
            // remove sql to create main table
            $sql = array_diff_key($sql, array(
                $modelTableName => ''
            ));
        }
        foreach ($sql as $query) {
            $connection->expr($query)->execute();
        }
        return true;
    }

    public function getRelationTable(ModelDescription $from, ModelDescription $to, ?string $relationName = null): String
    {
        $hay = array(
            strtolower($from->model),
            strtolower($to->model)
        );
        sort($hay);
        $prefix = $from->getEngine()
            ->getSchema()
            ->getPrefix();
        return self::skipeName($prefix . $hay[0] . '_' . $hay[1] . '_assoc');
    }

    public function getTableName(ModelDescription $model): string
    {
        return str_replace('\\', '_', $this->prefix . $model->table);
    }

    public function getAssocField(ModelDescription $model, ?string $relationName = null): String
    {
        $name = self::skipeName(strtolower($model->model) . '_id');
        $name = $this->qn($name);
        return $name;
    }

    /**
     * Gets attributes to put into the DB
     *
     * @param ModelDescription $md
     * @param mixed $model
     */
    public function getFields(ModelDescription $md)
    {
        $field = [];
        foreach ($md as $name => $property) {
            $field[$name] = $this->getFieldName($md, $property, true);
        }
        return $field;
    }

    /**
     * Get db field name for the $name attribute of the model
     *
     * The name may contains the alias (e.g. account.login) or a property name.
     * The result is a valid DB name contians the alias.
     *
     * @param ModelDescription $md
     * @param string $name
     * @return string
     */
    public function getField(ModelDescription $md, string $name): string
    {
        $names = explode('.', $name);
        if (count($names) == 1) {
            return $this->getFieldName($md, $md->$name, true);
        }
        $name = $names[1];
        return $names[0] . '.' . $this->getFieldName($md, $md->$name, false);
    }

    /**
     * Convert property name to DB name
     *
     * @param ModelDescription $md
     * @param ModelProperty $property
     * @param bool $joinTable
     *            The table name is added to property if it is ture otherwise the porperty name will be returned
     */
    public function getFieldName(ModelDescription $md, ModelProperty $property, ?bool $joinTable = true): string
    {
        if ($joinTable) {
            return $this->getTableName($md) . '.' . $property->name;
        }
        return $property->name;
    }

    /**
     * Converts $view fields into valid DB fields
     *
     * @param ModelDescription $md
     *            Reference data model description
     * @param array $view
     *            A view based on Pluf/Data specification
     */
    public function getViewFields(ModelDescription $md, array $view): array
    {
        if (! isset($view['field'])) {
            return [];
        }
        $fields = [];
        foreach ($view['field'] as $name => $field) {
            if ($field instanceof Expression) {
                $fields[$name] = $field;
                continue;
            }

            // extract alias
            $names = explode('.', $field);

            // get model description
            $cmd = $md;
            if (count($names) > 1) {
                // find model in joins
                foreach ($view['join'] as $join) {
                    if (isset($join['alias']) && $join['alias'] == $names[0]) {
                        $cmd = ModelDescription::getInstance($join['model']);
                        break;
                    }
                }
                $fields[$name] = $names[0] . '.' . $this->getField($cmd, $field, false);
            } else {
                $fields[$name] = $this->getField($md, $field, true);
            }
        }
        return $fields;
    }

    /**
     * Converts data join in $view to DB join
     *
     * @param ModelDescription $ref
     *            The reference data model description
     * @param array $view
     *            Data view to convert
     * @return array an array of db views
     */
    public function getViewJoins(ModelDescription $ref, array $view): array
    {
        $joins = [];
        if (! isset($view['join'])) {
            return $joins;
        }
        foreach ($view['join'] as $join) {
            $jmd = ModelDescription::getInstance($join['model']);

            $pn = $join['property'];
            $ftable = $this->getFieldName($jmd, $jmd->$pn, true);
            if (isset($join['alias'])) {
                $ftable .= ' ' . $join['alias'];
            }
            $pn = $join['masterProperty'];
            $joins[] = [
                $ftable,
                $this->getFieldName($ref, $ref->$pn),
                $join['kind']
            ];
        }
        return $joins;
    }

    /**
     * Converts filters form Data view into DB filter
     *
     * @param ModelDescription $md
     * @param array $view
     * @return array
     */
    public function getViewFilters(ModelDescription $md, $view)
    {
        if (! isset($view['filter'])) {
            return [];
        }
        return $this->convertDataFilters($md, $view['filter']);
    }

    public function getViewHaving(ModelDescription $md, $view)
    {
        if (! isset($view['having'])) {
            return [];
        }
        return $this->convertDataFilters($md, $view['having']);
    }

    public function getViewGroupBy(ModelDescription $md, $view)
    {
        if (! isset($view['group'])) {
            return null;
        }
        $groups = [];
        foreach ($view['group'] as $group) {
            if ($group instanceof Expression) {
                $groups[] = $group;
                continue;
            }

            $names = explode('.', $group);

            // get model description
            $cmd = $md;
            if (count($names) > 1) {
                // find model in joins
                foreach ($view['join'] as $join) {
                    if (isset($join['alias']) && $join['alias'] == $names[0]) {
                        $cmd = ModelDescription::getInstance($join['model']);
                        break;
                    }
                }
                $groups[] = $this->getField($cmd, $group);
            } else {
                $groups[] = $this->getField($md, $group);
            }
            return $groups;
        }
    }

    /**
     * Gets model values to put into the DB
     *
     * @param ModelDescription $md
     * @param mixed $model
     */
    public function getValues(ModelDescription $md, $model)
    {
        $values = [];
        foreach ($md as $name => $property) {
            if ($name == 'id') {
                // DB is responsible for ID
                continue;
            }
            if ($property->type == self::MANY_TO_MANY || $property->type == self::ONE_TO_MANY) {
                // Virtural attributes
                continue;
            }
            if ($property->type == self::MANY_TO_ONE) {
                // XXX: maso, 2020: check forenkey
            }
            $values[$name] = $this->toDb($property, $model->$name);
        }
        return $values;
    }

    /**
     * Converts Data filter from query into DB filter
     *
     * @param ModelDescription $md
     * @param Query $query
     * @return array
     */
    public function getQueryFilters(ModelDescription $md, Query $query)
    {
        return $this->convertDataFilters($md, $query->getFilter());
    }

    /**
     * Retrieve key relationships of a given model.
     *
     * @param string $model
     * @param string $type
     *            Relation Schema::MANY_TO_ONE or Schema::MANY_TO_MANY
     * @return array Key relationships.
     */
    public function getRelationKeysTo(ModelDescription $from, ModelDescription $to, $type)
    {
        $properies = [];
        foreach ($from as $name => $property) {
            if ($property->type === $type && $property->model === $to->type) {
                $properies[] = $name;
            }
        }
        return $properies;
    }

    /**
     * Creates new model and fill with data
     *
     * @param ModelDescription $md
     * @param mixed $data
     * @return mixed
     */
    public function newInstance(ModelDescription $md, $data)
    {
        $model = $md->newInstance();
        return $this->fillModel($md, $model, $data);
    }

    /**
     * Fills the model with data from DB
     *
     * @param ModelDescription $md
     * @param mixed $model
     */
    public function fillModel(ModelDescription $md, $model, $data)
    {
        foreach ($md as $property) {
            if ($property->model == self::MANY_TO_MANY) {
                continue;
            }
            if ($property->model == self::ONE_TO_MANY) {
                continue;
            }
            $name = $property->name;
            if (isset($data[$name])) {
                $model->$name = $this->fromDb($property, $data[$name]);
            }
        }
        return $model;
    }

    /**
     * Converts a data value into valid DB value
     *
     * @param ModelProperty $property
     * @param mixed $value
     * @return mixed
     */
    public function toDb(ModelProperty $property, $value)
    {
        $map = $this->type_cast[$property->type];
        return call_user_func_array($map[1], [
            $value
        ]);
    }

    /**
     * Converts a DB value into a valid data value
     *
     * @param ModelProperty $property
     * @param mixed $value
     * @return mixed
     */
    public function fromDb(ModelProperty $property, $value)
    {
        $map = $this->type_cast[$property->type];
        return call_user_func_array($map[0], [
            $value
        ]);
    }

    // XXX: maso, 2020: check if this is usefull anymoer
    public function skipeName(String $name): String
    {
        $name = str_replace('\\', '_', $name);
        return $name;
    }

    /*
     * converts list of data filter
     */
    private function convertDataFilters(ModelDescription $md, $dataFilter)
    {
        $filters = [];
        if (! isset($dataFilter) || count($dataFilter) == 0) {
            return $filters;
        }
        foreach ($dataFilter as $filter) {
            if ($filter instanceof Expression) {
                $filters[] = $filter;
                continue;
            }
            // or expression
            if (is_array($filter[0])) {
                $orFilters = [];
                foreach ($filter as $orFilter) {
                    $orFilters[] = $this->convertDataWhereToDb($md, $orFilter);
                }
                $filters[] = $orFilters;
                continue;
            }
            $filters[] = $this->convertDataWhereToDb($md, $filter);
        }
        return $filters;
    }

    /*
     * converts a filter to db filter
     */
    private function convertDataWhereToDb(ModelDescription $md, $filter)
    {
        if ($filter instanceof Expression) {
            return $filter;
        }

        $dfilter = [];
        $names = explode('.', $filter[0]);
        if (count($names) == 1) {
            $name = $names[0];
            $property = $md->$name;
            $dfilter[] = $this->getFieldName($md, $property);
        } else {
            $name = $names[1];
            $property = $md->$name;
            $dfilter[] = $names[0] . '.' . $this->getFieldName($md, $property, false);
        }

        if (count($filter) == 2) {
            $value = $filter[1];
        } else {
            $value = $filter[2];
            $dfilter[] = $filter[1]; // operation = > < in , ..
        }

        if ($value instanceof Expression) {
            $dfilter[] = $value;
        } else {
            $dfilter[] = $this->toDb($property, $value);
        }
        return $dfilter;
    }

    /*
     * -----------------------------------------------------------------
     * Abstract Part
     * -----------------------------------------------------------------
     */

    /**
     * Quote the column name.
     *
     * @param
     *            string Name of the column
     * @return string Escaped name
     */
    public abstract function qn(string $name): string;

    public abstract function createTableQueries(ModelDescription $model): array;

    /**
     * Get the SQL to drop the tables corresponding to the model.
     *
     * @param ModelDescription $model
     *            Model to create sql for
     * @return array SQL strings ready to execute.
     */
    public abstract function dropTableQueries(ModelDescription $model): array;

    public abstract function createIndexQueries(ModelDescription $model): array;

    public abstract function createConstraintQueries(ModelDescription $model): array;

    public abstract function dropConstraintQueries(ModelDescription $model): array;

    /**
     * Creates new instance of the schema
     *
     * @param Options $options
     * @throws Exception
     * @return Schema
     */
    public static function getInstance(Options $options): Schema
    {
        $type = $options->engine;
        if (! isset($type)) {
            $type = 'sqlite';
        }
        switch ($type) {
            case 'mysql':
                $engine = new Schema\MySQLSchema($options->startsWith('mysql_', true));
                break;
            case 'sqlite':
                $engine = new Schema\SQLiteSchema($options->startsWith('sqlite_', true));
                break;
            default:
                throw new Exception('Engine type "' . $type . '" is not supported with Pluf Data Schema.');
        }
        return $engine;
    }

    /**
     * Identity function.
     *
     * @params
     *            mixed Value
     * @return mixed Value
     */
    public static function identityFromDb($val)
    {
        return $val;
    }

    /**
     * Identity function.
     *
     * @param
     *            mixed Value.
     * @param
     *            object Database handler.
     * @return string Ready to use for SQL.
     */
    public static function identityToDb($val)
    {
        if (null === $val) {
            return null;
        }
        return $val;
    }

    public static function serializedFromDb($val)
    {
        if ($val) {
            return unserialize($val);
        }
        return $val;
    }

    public static function serializedToDb($val)
    {
        if (null === $val) {
            return null;
        }
        return serialize($val);
    }

    public static function compressedFromDb($val)
    {
        return ($val) ? gzinflate($val) : $val;
    }

    public static function compressedToDb($val)
    {
        return (null === $val) ? null : gzdeflate($val, 9);
    }

    public static function booleanFromDb($val)
    {
        if ($val) {
            return true;
        }
        return false;
    }

    public static function booleanToDb($val)
    {
        if (null === $val) {
            return null;
        }
        if ($val) {
            return '1';
        }
        return '0';
    }

    public static function integerFromDb($val)
    {
        return (null === $val) ? null : (int) $val;
    }

    public static function integerToDb($val)
    {
        return (null === $val) ? null : (string) (int) $val;
    }

    public static function floatFromDb($val)
    {
        return (null === $val) ? null : (float) $val;
    }

    public static function floatToDb($val)
    {
        return (null === $val) ? null : (string) (float) $val;
    }

    public static function passwordToDb($val)
    {
        $exp = explode(':', $val);
        if (in_array($exp[0], array(
            'sha1',
            'md5',
            'crc32'
        ))) {
            return $val;
        }
        // We need to hash the value.
        $salt = Pluf_Utils::getRandomString(5);
        return 'sha1:' . $salt . ':' . sha1($salt . $val);
    }

    public static function slugFromDB($val)
    {
        return $val;
    }

    public static function slugToDB($val)
    {
        return $val;
    }
}


