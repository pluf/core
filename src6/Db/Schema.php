<?php
namespace Pluf\Db;

use Pluf\Exception;
use Pluf\Options;
use Pluf;
use Pluf_Model;
use Pluf_SQL;
use Pluf_Tenant;

/**
 * Create the schema of a given Pluf_Model for a given database.
 *
 * @author maso
 *        
 */
abstract class Schema
{

    protected string $prefix = '';

    /**
     * Database connection object.
     */
    private ?Engine $con = null;

    /**
     * Schema generator object corresponding to the database.
     */
    public $schema = null;

    function __construct(Engine $db, ?Options $options = null)
    {
        $this->con = $db;
        if (isset($options)) {
            $this->prefix = $options->table_prefix;
            if (! isset($this->prefix)) {
                $this->prefix = '';
            }
        }
    }

    /**
     * Given a column name or a string with column names in the format
     * "column1, column2, column3", returns the escaped correctly
     * quoted column names.
     * This is good for index creation.
     *
     * @param
     *            string Column
     * @param
     *            Pluf_DB DB handler
     * @return string Quoted for the DB column(s)
     */
    public static function quoteColumn(string $col, \Pluf\Db\Schema $schema): string
    {
        if (false !== strpos($col, ',')) {
            $cols = explode(',', $col);
        } else {
            $cols = array(
                $col
            );
        }
        $res = array();
        foreach ($cols as $col) {
            $res[] = $schema->qn(trim($col));
        }
        return implode(', ', $res);
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

    public function getRelationTable(Pluf_Model $from, Pluf_Model $to, ?string $relationName = null): String
    {
        $hay = array(
            strtolower($from->_a['model']),
            strtolower($to->_a['model'])
        );
        sort($hay);
        $prefix = $from->getEngine()
            ->getSchema()
            ->getPrefix();
        return self::skipeName($prefix . $hay[0] . '_' . $hay[1] . '_assoc');
    }

    public function createQuery(Pluf_Model $model)
    {
        if (Pluf::f('multitenant', false) && $model->_a['multitenant']) {
            $model->tenant = Pluf_Tenant::getCurrent();
        }

        // $engine = $model->getEngine();

        $icols = array();
        $ivals = array();
        // $assoc = array();

        $raw = false;

        foreach ($model->_a['cols'] as $col => $description) {
            $type = $description['type'];
            $val = $model->_data[$col];
            if ($col == 'id' && ! $raw) {
                continue;
            } elseif ($type === Engine::MANY_TO_MANY) {
                // // If is a defined array, we need to associate.
                // if (is_array($this->_data[$col])) {
                // $assoc[$description['model']] = $this->_data[$col];
                // }
                continue;
            }
            $icols[] = $this->qn($col);
            $ivals[] = $val;
        }

        return new Pluf_SQL('INSERT INTO ' . $this->getTableName($model) . '(' . implode(',', $icols) . ') VALUES (' . implode(', ', array_fill(0, sizeof($ivals), '%s')) . ')', $ivals);

        // TODO: maso, 2020 support multi query and insert
        // $this->_con->execute($req);
        // if (! $raw) {
        // if (false === ($id = $this->_con->getLastID())) {
        // throw new \Pluf\Exception($this->_con->getError());
        // }
        // $this->_data['id'] = $id;
        // }
        // foreach ($assoc as $model => $ids) {
        // $this->batchAssoc($model, $ids);
        // }
        // if (! $raw) {
        // $this->postSave(true);
        // }
        // return true;
    }

    public function selectByIdQuery(Pluf_Model $model, $id)
    {
        $engine = $model->getEngine();
        $params = array(
            $engine->toDb($id, Engine::SEQUENCE)
        );
        if (Pluf::f('multitenant', false) && $model->_a['multitenant']) {
            $req = 'SELECT * FROM ' . $this->getTableName($model) . ' WHERE tenant=%s AND id=%s';
            array_unshift($params, Pluf_Tenant::getCurrent()->id);
        } else {
            $req = 'SELECT * FROM ' . $this->getTableName($model) . ' WHERE id=%s';
        }
        return new Pluf_SQL($req, $params);
    }

    function updateQuery(Pluf_Model $model, $where = '')
    {
        $engine = $model->getEngine();
        $params = array();

        $fields = [];
        foreach ($model->_a['cols'] as $col => $description) {
            $type = $description['type'];
            if ($col == 'id') {
                continue;
            } elseif ($type == Engine::MANY_TO_MANY) {
                continue;
            }
            $fields[] = $this->qn($col) . '=%s';
            $params[] = $model->$col;
        }

        $req = 'UPDATE ' . $this->getTableName($model) . ' SET ' . implode(',', $fields);
        if (strlen($where) > 0) {
            $req .= ' WHERE ' . $where;
        } else {
            $req .= ' WHERE id = ' . $engine->toDb($model->id, Engine::SEQUENCE);
        }
        return new Pluf_SQL($req, $params);
    }

    function selectListQuery(Pluf_Model $model, array $p = [])
    {
        $default = array(
            'view' => null,
            'group' => null,
            'filter' => null,
            'order' => null,
            'start' => null,
            'select' => null,
            'nb' => null,
            'count' => false
        );
        $p = array_merge($default, $p);
        if (! is_null($p['view']) && ! isset($model->_a['views'][$p['view']])) {
            throw new Exception(sprintf('The view "%s" is not defined.', $p['view']));
        }
        $query = array(
            'select' => $model->getSelect(),
            'from' => $model->_a['table'],
            'join' => '',
            'where' => '',
            'group' => '',
            'having' => '',
            'order' => '',
            'limit' => '',
            'props' => array()
        );
        $params = [];
        if (! is_null($p['view'])) {
            $query = array_merge($query, $model->_a['views'][$p['view']]);
        }
        if (! is_null($p['select'])) {
            $query['select'] = $p['select'];
        }
        if (! is_null($p['group'])) {
            $query['group'] = $p['group'];
        }
        if (! is_null($p['filter'])) {
            if (is_array($p['filter'])) {
                $p['filter'] = implode(' AND ', $p['filter']);
            }
            if (strlen($query['where']) > 0) {
                $query['where'] .= ' AND ';
            }
            $query['where'] .= ' (' . Pluf_SQL::cleanString($p['filter']) . ') ';
        }
        // Multi-Tenant filter
        if (Pluf::f('multitenant', false) && $model->_a['multitenant']) {
            // Note: Hadi, 1395-11-26: Table should be set before tenant field.
            // It is to avoid ambiguous problem in join tables which both have tenant field.
            $sql = new Pluf_SQL($model->getSqlTable() . '.tenant=%s', array(
                Pluf_Tenant::current()->id
            ));
            if (strlen($query['where']) > 0) {
                $query['where'] = ' AND ' . $query['where'];
            }
            $query['where'] = $sql->gen() . $query['where'];
        }
        if (! is_null($p['order'])) {
            if (is_array($p['order'])) {
                $p['order'] = implode(', ', $p['order']);
            }
            if (strlen($query['order']) > 0 and strlen($p['order']) > 0) {
                $query['order'] .= ', ';
            }
            $query['order'] .= $p['order'];
        }
        if (! is_null($p['start']) && is_null($p['nb'])) {
            $p['nb'] = 10000000;
        }
        if (! is_null($p['start'])) {
            if ($p['start'] != 0) {
                $p['start'] = (int) $p['start'];
            }
            $p['nb'] = (int) $p['nb'];
            $query['limit'] = 'LIMIT ' . $p['nb'] . ' OFFSET ' . $p['start'];
        }
        if (! is_null($p['nb']) && is_null($p['start'])) {
            $p['nb'] = (int) $p['nb'];
            $query['limit'] = 'LIMIT ' . $p['nb'];
        }
        if ($p['count'] == true) {
            if (isset($query['select_count'])) {
                $query['select'] = $query['select_count'];
            } else {
                $query['select'] = 'COUNT(*) as nb_items';
            }
            $query['order'] = '';
            $query['limit'] = '';
        }
        $req = 'SELECT ' . $query['select'] . ' FROM ' . $this->getPrefix() . $query['from'] . ' ' . $query['join'];
        if (strlen($query['where'])) {
            $req .= "\n" . 'WHERE ' . $query['where'];
        }
        if (strlen($query['group'])) {
            $req .= "\n" . 'GROUP BY ' . $query['group'];
        }
        if (strlen($query['having'])) {
            $req .= "\n" . 'HAVING ' . $query['having'];
        }
        if (strlen($query['order'])) {
            $req .= "\n" . 'ORDER BY ' . $query['order'];
        }
        if (strlen($query['limit'])) {
            $req .= "\n" . $query['limit'];
        }

        return new Pluf_SQL($req, $params);
    }

    /**
     * Inserts new relation from-to
     *
     * @param Pluf_Model $from
     * @param Pluf_Model $to
     * @param string $assocName
     * @return Pluf_SQL
     */
    public function createRelationQuery(Pluf_Model $from, Pluf_Model $to, ?string $relationName = null): Pluf_SQL
    {
        return new Pluf_SQL('INSERT INTO ' . $this->getRelationTable($from, $to, $relationName) . //
        '(' . $this->getAssocField($from, $relationName) . ', ' . $this->getAssocField($to, $relationName) . ') VALUES (%s, %s)', array(
            $from->id,
            $to->id
        ));
    }

    /**
     * Delete the relation from-to
     *
     * @param Pluf_Model $from
     * @param Pluf_Model $to
     * @param string $assocName
     * @return Pluf_SQL
     */
    public function deleteRelationQuery(Pluf_Model $from, Pluf_Model $to, ?string $relationName = null): Pluf_SQL
    {
        return new Pluf_SQL('DELETE FROM ' . $this->getRelationTable($from, $to, $relationName) . //
        'WHERE ' . $this->getAssocField($from, $relationName) . '=%s ANS ' . $this->getAssocField($to, $relationName) . '%s=%s', array(
            $from->id,
            $to->id
        ));
    }

    public function getTableName(Pluf_Model $model): string
    {
        return str_replace('\\', '_', $this->prefix . $model->_a['table']);
    }

    public function getAssocField($model, $relationName): String
    {
        $name = self::skipeName(strtolower($model->_a['model']) . '_id');
        $name = $this->qn($name);
        return $name;
    }

    public abstract function qn(string $name): string;

    public abstract function createTableQueries(Pluf_Model $model): array;

    public abstract function dropTableQueries(Pluf_Model $model): array;

    public static function skipeName(String $name): String
    {
        $name = str_replace('\\', '_', $name);
        return $name;
    }
}


