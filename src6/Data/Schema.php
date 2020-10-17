<?php
namespace Pluf\Data;

use Pluf\Options;
use Pluf\Db\Connection;
use Pluf\Db\Expression;
use Pluf\Utils;

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

    public const OUTER_JOIN = 'outer';

    /**
     * Relations types
     * {
     */
    public const MANY_TO_MANY = 'Manytomany';

    // Others has foreing key to it
    public const ONE_TO_MANY = 'Onetomany';

    // foreignkey
    public const MANY_TO_ONE = 'Manytoone';

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
        self::MANY_TO_ONE => array(
            '\Pluf\Data\Schema::sequenceFromDb',
            '\Pluf\Data\Schema::sequenceToDb'
        ),
        self::FOREIGNKEY => array(
            '\Pluf\Data\Schema::sequenceFromDb',
            '\Pluf\Data\Schema::sequenceToDb'
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
            '\Pluf\Data\Schema::sequenceFromDb',
            '\Pluf\Data\Schema::sequenceToDb'
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

    /**
     * Fetchs joine table for Many To Many relations
     *
     * @param ModelDescription $smd
     * @param ModelDescription $tmd
     * @param ModelProperty $relation
     * @throws Exception
     * @return string
     */
    public function getRelationTable(ModelDescription $smd, ModelDescription $tmd, ModelProperty $relation): string
    {
        if ($relation->type != self::MANY_TO_MANY) {
            throw new Exception([
                'message' => 'The relation {name} from {srouce} is not assigned to a table.',
                'source' => $smd->type,
                'name' => $relation->name
            ]);
        }

        // // joineModel
        // $joineModel = $relation->inverseJoinModel;
        // if (isset($joineModel)) {
        // $joineModelDescription = ModelDescription::getInstance($joineModel);
        // return $this->getTableName($joineModelDescription);
        // }

        // joineTable
        $joineTable = $relation->joinTable;
        if (isset($joineTable)) {
            return $this->prefix . $joineTable;
        }

        // crate default table name
        $hay = array(
            strtolower($smd->type),
            strtolower($tmd->type)
        );
        sort($hay);
        return self::skipeName($this->prefix . $hay[0] . '_' . $hay[1] . '_assoc');
    }

    /**
     * Gets source relation columne name
     *
     * @param ModelDescription $smd
     *            Source model in relation
     * @param ModelDescription $tmd
     *            Target model in relation
     * @param ModelProperty $relation
     *            The relation description
     * @return string name of the columne in the relation
     */
    public function getRelationSourceField(ModelDescription $smd, ModelDescription $tmd, ModelProperty $relation, ?bool $qn = true): string
    {
        $name = $relation->joinColumne;
        if (! isset($name)) {
            $name = self::skipeName(strtolower($smd->type) . '_id');
        }
        if ($qn) {
            $name = $this->qn($name);
        }
        return $name;
    }

    /**
     * Gets target relation columne name
     *
     * @param ModelDescription $smd
     *            Source model in relation
     * @param ModelDescription $tmd
     *            Target model in relation
     * @param ModelProperty $relation
     *            The relation description
     * @return string name of the columne in the relation
     */
    public function getRelationTargetField(ModelDescription $smd, ModelDescription $tmd, ModelProperty $relation, ?bool $qut = true): string
    {
        $name = $relation->inverseJoinColumne;
        if (! isset($name)) {
            $name = self::skipeName(strtolower($tmd->type) . '_id');
        }
        if ($qut) {
            $name = $this->qn($name);
        }
        return $name;
    }

    public function getAssocField(ModelDescription $model, ?string $relationName = null): String
    {
        $name = self::skipeName(strtolower($model->model) . '_id');
        $name = $this->qn($name);
        return $name;
    }

    /**
     * Generate real table name for model
     *
     * @param ModelDescription $modelDescription
     *            to fetch table name for
     * @return string real table name
     */
    public function getTableName(ModelDescription $modelDescription): string
    {
        return str_replace('\\', '_', $this->prefix . $modelDescription->table);
    }

    /**
     * Gets attributes to put into the DB
     *
     * @param ModelDescription $md
     * @param mixed $alias
     *            to be used for all properties
     */
    public function getFields(ModelDescription $md, ?string $alias = null)
    {
        $field = [];
        $autoPrefix = ! isset($alias);
        if ($autoPrefix) {
            $alias = '';
        } else {
            $alias = $alias . '.';
        }
        foreach ($md as $name => $property) {
            if ($property->type == self::MANY_TO_MANY || $property->type == self::MANY_TO_ONE || $property->type == self::ONE_TO_MANY) {
                continue;
            }
            $field[$name] = $alias . $this->getFieldName($md, $property, $autoPrefix);
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
        $name = $property->columne;
        if (! isset($name)) {
            $name = $property->name;
        }
        if ($joinTable) {
            return $this->getTableName($md) . '.' . $name;
        }
        return $name;
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
                        if (isset($join['inverseJoinModel'])) {
                            $cmd = ModelDescription::getInstance($join['inverseJoinModel']);
                            break;
                        }
                        $jproname = $join['joinProperty'];
                        $joinProperty = $md->$jproname;
                        $cmd = ModelDescription::getInstance($joinProperty->inverseJoinModel);
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
    public function getViewJoins(ModelDescription $joinModel, array $view): array
    {
        $joins = [];
        if (! isset($view['join'])) {
            return $joins;
        }
        foreach ($view['join'] as $join) {
            $type = self::LEFT_JOIN;
            $alias = null;
            if (isset($join['type'])) {
                $type = $join['type'];
            }
            if (isset($join['alias'])) {
                $alias = $join['alias'];
            }

            /*
             * Native joing
             *
             * TODO: maso, 2020: support joinProperty
             */
            if (isset($join['joinTable'])) {
                $ftable = $join['joinTable'] . '.' . $join['inverseJoinColumne'];
                if (isset($alias)) {
                    $ftable = $ftable . ' ' . $alias;
                }
                $joins[] = [
                    $ftable,
                    $join['joinColumne'],
                    $type
                ];
                continue;
            }

            /*
             * Data Join
             */
            $jproName = $join['joinProperty'];
            if (! isset($jproName)) {
                $jproName = 'id';
            }
            /*
             * required data
             */
            $joinProperty = $joinModel->$jproName;
            $inverseJoinModel = null;
            $inverseJoinProperty = null;

            /*
             * Fetch data from model
             */
            switch ($joinProperty->type) {
                case self::ONE_TO_MANY:
                case self::MANY_TO_ONE:
                case self::MANY_TO_MANY:
                    // Forign key
                    $inverseJoinModel = ModelDescription::getInstance($joinProperty->inverseJoinModel);
                    $inverseJoinPropertyName = $joinProperty->inverseJoinProperty;
                    if (! isset($inverseJoinPropertyName)) {
                        $inverseJoinPropertyName = 'id';
                    }
                    $inverseJoinProperty = $inverseJoinModel->$inverseJoinPropertyName;
                    break;
                default:
                    $inverseJoinModel = ModelDescription::getInstance($join['inverseJoinModel']);
                    $inverseJoinPropertyName = $join['inverseJoinProperty'];
                    if (! isset($inverseJoinPropertyName)) {
                        $inverseJoinPropertyName = 'id';
                    }
                    $inverseJoinProperty = $inverseJoinModel->$inverseJoinPropertyName;
                    break;
            }

            $dbJoins = $this->createDbJoin($joinModel, $joinProperty, $inverseJoinModel, $inverseJoinProperty, $type, $alias);
            $joins = array_merge($joins, $dbJoins);
        }
        return $joins;
    }

    private function createDbJoin(ModelDescription $joinModel, ModelProperty $joinProperty, //
    ModelDescription $inverseJoinModel, ModelProperty $inverseJoinProperty, //
    string $type, ?string $alias = ''): array
    {
        $joins = [];

        /*
         * Fetch data from model
         */
        switch ($joinProperty->type) {
            case self::MANY_TO_MANY:
                // First
                $rtable = $this->getRelationTable($joinModel, $inverseJoinModel, $joinProperty);

                $propertyName = $joinProperty->joinProperty;
                if (! isset($propertyName)) {
                    $propertyName = 'id';
                }
                $joinProperty = $joinModel->$propertyName;

                $joins[] = [
                    $rtable . '.' . $this->getRelationSourceField($joinModel, $inverseJoinModel, $joinProperty),
                    $this->getFieldName($joinModel, $joinProperty, true),
                    $type
                ];

                // second
                $ttableField = $this->getRelationTargetField($joinModel, $inverseJoinModel, $joinProperty);
                $joins[] = [
                    $rtable . '.' . $ttableField . ' ' . $alias,
                    $this->getFieldName($inverseJoinModel, $inverseJoinProperty, true),
                    $type
                ];
                break;
            case self::ONE_TO_MANY:
                $ftable = $this->getFieldName($inverseJoinModel, $inverseJoinProperty, true);
                if (isset($alias)) {
                    $ftable .= ' ' . $alias;
                }
                // get real property
                $propertyName = $joinProperty->joinProperty;
                if (! isset($propertyName)) {
                    $propertyName = 'id';
                }
                $rjp = $joinModel->$propertyName;
                $joins[] = [
                    $ftable,
                    $this->getFieldName($joinModel, $rjp),
                    $type
                ];
                break;
            case self::MANY_TO_ONE:
                if ($inverseJoinProperty->type == self::ONE_TO_MANY) {
                    $propertyName = $inverseJoinModel->joinProperty;
                    if (! isset($propertyName)) {
                        $propertyName = 'id';
                    }
                    $inverseJoinProperty = $inverseJoinModel->$propertyName;
                }
                $ftable = $this->getFieldName($inverseJoinModel, $inverseJoinProperty, true);
                if (isset($alias)) {
                    $ftable .= ' ' . $alias;
                }
                $joins[] = [
                    $ftable,
                    $this->getFieldName($joinModel, $joinProperty),
                    $type
                ];
                break;
            default:
                $ftable = $this->getFieldName($inverseJoinModel, $inverseJoinProperty, true);
                if (isset($alias)) {
                    $ftable .= ' ' . $alias;
                }
                $joins[] = [
                    $ftable,
                    $this->getFieldName($joinModel, $joinProperty),
                    $type
                ];
                break;
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
        return $this->convertViewDataFilters($md, $view, $view['filter']);
    }

    public function getViewHaving(ModelDescription $md, $view)
    {
        if (! isset($view['having'])) {
            return [];
        }
        return $this->convertViewDataFilters($md, $view, $view['having']);
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
                        if (isset($join['inverseJoinModel'])) {
                            $cmd = ModelDescription::getInstance($join['inverseJoinModel']);
                            break;
                        }
                        $jproname = $join['joinProperty'];
                        $joinProperty = $md->$jproname;
                        $cmd = ModelDescription::getInstance($joinProperty->inverseJoinModel);
                        break;
                    }
                }
                $groups[] = $this->getField($cmd, $group);
            } else {
                $groups[] = $this->getField($md, $group);
            }
        }
        if (count($groups) == 0) {
            return null;
        }
        return $groups;
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
            if ($property->type == self::MANY_TO_MANY || $property->type == self::ONE_TO_MANY || $property->isMapped()) {
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
            if ($property->type == self::MANY_TO_MANY) {
                continue;
            }
            if ($property->type == self::ONE_TO_MANY) {
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
            $value,
            $property
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
            $value,
            $property
        ]);
    }

    // XXX: maso, 2020: check if this is usefull anymoer
    public function skipeName(String $name): String
    {
        $name = str_replace('\\', '_', $name);
        return $name;
    }

    private function convertViewDataFilters(ModelDescription $md, $view, $dataFilter)
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
                    $orFilters[] = $this->convertViewDataWhereToDb($md, $view, $orFilter);
                }
                $filters[] = $orFilters;
                continue;
            }
            $filters[] = $this->convertViewDataWhereToDb($md, $view, $filter);
        }
        return $filters;
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
     *
     * NOTE: all attribute are considerd to be from $md
     */
    private function convertDataWhereToDb(ModelDescription $md, $filter)
    {
        if ($filter instanceof Expression) {
            return $filter;
        }

        $dfilter = [];
        $names = explode('.', $filter[0]);
        if (count($names) == 1) {
            $name = $filter[0];
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
     * converts a filter to db filter
     *
     * Note: search alias in view joins
     */
    private function convertViewDataWhereToDb(ModelDescription $md, $view, $filter)
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
            foreach ($view['join'] as $join) {
                if (isset($join['alias']) && $join['alias'] == $names[0]) {
                    /*
                     * Native Join
                     */
                    if (isset($join['joinTable'])) {
                        return $filter;
                    }
                    /*
                     * Data Join
                     */
                    if (isset($join['inverseJoinModel'])) {
                        $cmd = ModelDescription::getInstance($join['inverseJoinModel']);
                        break;
                    }
                    $jproname = $join['joinProperty'];
                    $joinProperty = $md->$jproname;
                    $cmd = ModelDescription::getInstance($joinProperty->inverseJoinModel);
                    break;
                }
            }
            $property = $cmd->$name;
            $dfilter[] = $names[0] . '.' . $this->getFieldName($cmd, $property, false);
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

    public static function sequenceFromDb($val, ModelProperty $property)
    {
        return $val;
    }

    public static function sequenceToDb($val, ModelProperty $property)
    {
        if (! isset($val)) {
            return null;
        }
        switch ($property->type) {
            case self::SEQUENCE:
            case self::MANY_TO_ONE:
            case self::FOREIGNKEY:
                if ($val instanceof \Pluf_Model) {
                    return $val->id;
                }
                if (is_numeric($val)) {
                    return $val;
                }
            default:
                throw new Exception([
                    'message' => 'Property value is not convertable to db'
                ]);
        }
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
        $salt = Utils::getRandomString(5);
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


