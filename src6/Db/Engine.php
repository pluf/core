<?php
namespace Pluf\Db;

use Pluf\Options;
use Pluf_Utils;
use WKT;
use geoPHP;

/**
 *
 * @author maso
 *        
 */
abstract class Engine
{

    public const MANY_TO_MANY = 'Manytomany';

    public const FOREIGNKEY = 'Foreignkey';

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

    private ?Options $options = null;

    private ?Schema $schema = null;

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
            '\Pluf\Db\Engine::booleanFromDb',
            '\Pluf\Db\Engine::booleanToDb'
        ),
        self::DATE => array(
            '\Pluf\Db\Engine::identityFromDb',
            '\Pluf\Db\Engine::identityToDb'
        ),
        self::DATETIME => array(
            '\Pluf\Db\Engine::identityFromDb',
            '\Pluf\Db\Engine::identityToDb'
        ),
        self::EMAIL => array(
            '\Pluf\Db\Engine::identityFromDb',
            '\Pluf\Db\Engine::identityToDb'
        ),
        self::FILE => array(
            '\Pluf\Db\Engine::identityFromDb',
            '\Pluf\Db\Engine::identityToDb'
        ),
        self::FLOAT => array(
            '\Pluf\Db\Engine::floatFromDb',
            '\Pluf\Db\Engine::floatToDb'
        ),
        self::FOREIGNKEY => array(
            '\Pluf\Db\Engine::integerFromDb',
            '\Pluf\Db\Engine::integerToDb'
        ),
        self::INTEGER => array(
            '\Pluf\Db\Engine::integerFromDb',
            '\Pluf\Db\Engine::integerToDb'
        ),
        self::PASSWORD => array(
            '\Pluf\Db\Engine::identityFromDb',
            '\Pluf\Db\Engine::passwordToDb'
        ),
        self::SEQUENCE => array(
            '\Pluf\Db\Engine::integerFromDb',
            '\Pluf\Db\Engine::integerToDb'
        ),
        self::SLUG => array(
            '\Pluf\Db\Engine::identityFromDb',
            '\Pluf\Db\Engine::slugToDb'
        ),
        self::TEXT => array(
            '\Pluf\Db\Engine::identityFromDb',
            '\Pluf\Db\Engine::identityToDb'
        ),
        self::VARCHAR => array(
            '\Pluf\Db\Engine::identityFromDb',
            '\Pluf\Db\Engine::identityToDb'
        ),
        self::SERIALIZED => array(
            '\Pluf\Db\Engine::serializedFromDb',
            '\Pluf\Db\Engine::serializedToDb'
        ),
        self::COMPRESSED => array(
            '\Pluf\Db\Engine::compressedFromDb',
            '\Pluf\Db\Engine::compressedToDb'
        ),
        self::GEOMETRY => array(
            '\Pluf\Db\Engine::geometryFromDb',
            '\Pluf\Db\Engine::geometryToDb'
        )
    );

    /**
     * Creates new instance of an engine
     */
    function __construct(Options $options)
    {
        // set local options
        $this->options = $options;
    }

    /**
     *
     * @return Options which is used to create
     */
    public function getOptions(): ?Options
    {
        return $this->options;
    }

    /**
     *
     * @return mixed
     */
    public function getSchema(): ?Schema
    {
        return $this->schema;
    }

    /**
     * Prepare the value to be put in the DB.
     *
     * @param
     *            mixed Value.
     * @param
     *            string Column name.
     * @return string SQL ready string.
     */
    function toDb($val, $type)
    {
        $m = $this->type_cast[$type][1];
        return $m($val, $this);
    }

    /**
     * Get the value from the DB.
     *
     * Create DB field and returns. The field type is used as the output
     * value type.
     *
     * @param
     *            mixed Value.
     * @param
     *            string Column name.
     * @return mixed Value.
     */
    function fromDb($val, $type)
    {
        $m = $this->type_cast[$type][0];
        return ($m == 'Pluf_DB_IdentityFromDb') ? $val : $m($val);
    }

    public abstract function isLive(): bool;

    public abstract function quote(string $string, int $parameter_type = null);

    public abstract function execute($query);

    /**
     * Gets last created Model ID
     *
     * In insert query, last created model id will be saved in engine.
     *
     * @return int last created model id
     */
    public abstract function getLastID(): int;

    public static function getInstance(Options $options): Engine
    {
        $engine = $options->engine;
        $con = new $engine($options);

        // Create schema
        $schemaName = $options->schema;
        $schemaOption = $options->startsWith('schema_', true);
        $con->schema = new $schemaName($con, $schemaOption);

        return $con;
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
    public static function identityToDb($val, $db)
    {
        if (null === $val) {
            return 'NULL';
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

    public static function serializedToDb($val, $db)
    {
        if (null === $val) {
            return 'NULL';
        }
        return $db->esc(serialize($val));
    }

    public static function compressedFromDb($val)
    {
        return ($val) ? gzinflate($val) : $val;
    }

    public static function compressedToDb($val, $db)
    {
        return (null === $val) ? 'NULL' : $db->esc(gzdeflate($val, 9));
    }

    public static function booleanFromDb($val)
    {
        if ($val) {
            return true;
        }
        return false;
    }

    public static function booleanToDb($val, $db)
    {
        if (null === $val) {
            return 'NULL';
        }
        if ($val) {
            return $db->esc('1');
        }
        return $db->esc('0');
    }

    public static function integerFromDb($val)
    {
        return (null === $val) ? null : (int) $val;
    }

    public static function integerToDb($val, $db)
    {
        return (null === $val) ? 'NULL' : (string) (int) $val;
    }

    public static function floatFromDb($val)
    {
        return (null === $val) ? null : (float) $val;
    }

    public static function floatToDb($val, $db)
    {
        return (null === $val) ? 'NULL' : (string) (float) $val;
    }

    public static function passwordToDb($val, $db)
    {
        $exp = explode(':', $val);
        if (in_array($exp[0], array(
            'sha1',
            'md5',
            'crc32'
        ))) {
            return $db->esc($val);
        }
        // We need to hash the value.
        $salt = Pluf_Utils::getRandomString(5);
        return $db->esc('sha1:' . $salt . ':' . sha1($salt . $val));
    }

    public static function slugFromDB($val)
    {}

    public static function slugToDB($val, $db)
    {
        // return $db->esc(Pluf_DB_Field_Slug::slugify($val));
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
        if ($val == null)
            return null;
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
        // return (null === $val || empty($val)) ? 'NULL' : "'" . $val . "'";
        // }
        return (null === $val || empty($val)) ? 'NULL' : (string) "GeometryFromText('" . $val . "')";
    }
}

