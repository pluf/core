<?php
namespace Pluf\Test\Data;

require_once 'Pluf.php';

use PHPUnit\Framework\TestCase;
use Pluf\Data\ModelDescription;
use Pluf\Data\Query;
use Pluf;

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class OldPlufModelTest extends TestCase
{

    /**
     *
     * @before
     */
    public function createDb()
    {
        Pluf::start([
            'db_dsn' => 'sqlite::memory:',
            'db_user' => null,
            'db_password' => null,
            'data_schema_engine' => 'sqlite',
            'data_schema_sqlite_prefix' => 'db' . rand() . '_'
        ]);
    }

    /**
     *
     * @test
     */
    public function testCreateAModel()
    {
        $model = new \Pluf\NoteBook\Book();
        $model->title = 'title' . rand();

        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($model));

        $this->assertTrue($model->create());
        $this->assertFalse($model->isAnonymous());
    }

    /**
     *
     * @test
     */
    public function testGetOneById()
    {
        $model = new \Pluf\NoteBook\Book();
        $model->title = 'title' . rand();

        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($model));
        $this->assertTrue($model->create());

        $model2 = new \Pluf\NoteBook\Book($model->id);
        $this->assertFalse($model2->isAnonymous());
        $this->assertEquals($model->title, $model2->title);
    }

    /**
     *
     * @test
     */
    public function testGetModel()
    {
        $model = new \Pluf\NoteBook\Book();
        $model->title = 'title' . rand();

        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($model));
        $this->assertTrue($model->create());

        $model2 = new \Pluf\NoteBook\Book();
        $model2->get($model->id);
        $this->assertFalse($model2->isAnonymous());
        $this->assertEquals($model->title, $model2->title);
    }

    /**
     *
     * @test
     */
    public function testGetOneModel()
    {
        $model = new \Pluf\NoteBook\Book();
        $model->title = 'title' . rand();

        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($model));
        $this->assertTrue($model->create());

        $model2 = new \Pluf\NoteBook\Book();
        $model2->get($model->id);
        $this->assertFalse($model2->isAnonymous());
        $this->assertEquals($model->title, $model2->title);
    }

    /**
     *
     * @test
     */
    public function testGetItemByWhere()
    {
        $model = new \Pluf\NoteBook\Book();
        $model->title = 'title' . rand();

        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($model));
        $this->assertTrue($model->create());

        // 1
        $list = $model->getList([
            'filter' => [
                [
                    'id',
                    $model->id
                ]
            ]
        ]);
        $this->assertEquals(1, sizeof($list));

        $model2 = $list[0];
        $model2->get($model->id);
        $this->assertFalse($model2->isAnonymous());
        $this->assertEquals($model->title, $model2->title);

        // 2
        $list = $model->getList([
            'filter' => [
                [
                    'title',
                    $model->title
                ]
            ]
        ]);
        $this->assertEquals(1, sizeof($list));

        $model2 = $list[0];
        $model2->get($model->id);
        $this->assertFalse($model2->isAnonymous());
        $this->assertEquals($model->title, $model2->title);

        // 3
        $list = $model->getList([
            'filter' => [
                [
                    'id',
                    $model->id
                ],
                [
                    'title',
                    $model->title
                ]
            ]
        ]);
        $this->assertEquals(1, sizeof($list));

        $model2 = $list[0];
        $model2->get($model->id);
        $this->assertFalse($model2->isAnonymous());
        $this->assertEquals($model->title, $model2->title);
    }

    /**
     *
     * @test
     */
    public function testGetItemByWhereAndOrder()
    {
        $model = new \Pluf\NoteBook\Book();
        $model->title = 'title' . rand();

        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($model));
        $this->assertTrue($model->create());
        $this->assertTrue($model->create());

        // 1
        $list = $model->getList([
            'filter' => [
                [
                    'title',
                    $model->title
                ]
            ],
            'order' => [
                'id' => Query::ORDER_ASC
            ]
        ]);
        $this->assertEquals(2, sizeof($list));
        $this->assertTrue($list[0]->id < $list[1]->id);

        // 1
        $list = $model->getList([
            'filter' => [
                [
                    'title',
                    $model->title
                ]
            ],
            'order' => [
                'id' => Query::ORDER_DESC
            ]
        ]);
        $this->assertEquals(2, sizeof($list));
        $this->assertTrue($list[0]->id > $list[1]->id);
    }

    /**
     *
     * @test
     */
    public function testGetItemByQuery()
    {
        $model = new \Pluf\NoteBook\Book();
        $model->title = 'title xxx' . rand();

        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($model));
        $this->assertTrue($model->create());

        // 1
        $list = $model->getList([
            'filter' => [
                [
                    'title',
                    'like',
                    '%xxx%'
                ]
            ]
        ]);
        $this->assertEquals(1, sizeof($list));

        // 1
        $list = $model->getList([
            'filter' => [
                [
                    'title',
                    'like',
                    '%yyy%'
                ]
            ]
        ]);
        $this->assertEquals(0, sizeof($list));
    }

    /**
     *
     * @test
     */
    public function testGetItemByQueryLimit()
    {
        $model = new \Pluf\NoteBook\Book();
        $model->title = 'title xxx' . rand();

        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($model));
        // crate 5 item
        $this->assertTrue($model->create());
        $this->assertTrue($model->create());
        $this->assertTrue($model->create());
        $this->assertTrue($model->create());
        $this->assertTrue($model->create());

        // 1
        $list = $model->getList([
            'start' => 0,
            'limit' => 2
        ]);
        $this->assertEquals(2, sizeof($list));

        // 2
        $list = $model->getList([
            'start' => 4,
            'limit' => 2
        ]);
        $this->assertEquals(1, sizeof($list));

        // 3
        $list = $model->getList([
            'start' => 0,
            'limit' => 10
        ]);
        $this->assertEquals(5, sizeof($list));
    }

    /**
     *
     * @test
     */
    public function testGetItemCount()
    {
        $model = new \Pluf\NoteBook\Book();
        $model->title = 'title xxx' . rand();

        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($model));
        // crate 5 item
        $this->assertTrue($model->create());
        $this->assertTrue($model->create());
        $this->assertTrue($model->create());
        $this->assertTrue($model->create());
        $this->assertTrue($model->create());

        // 1
        $count = $model->getList([
            'count' => true
        ]);
        $this->assertEquals(5, $count);

        // 3
        $count = $model->getList([
            'filter' => [
                [
                    'id',
                    '<',
                    2
                ]
            ],
            'count' => true
        ]);
        $this->assertEquals(1, $count);
    }

    /**
     *
     * @test
     */
    public function testGetEmptyBooksByView()
    {
        $book = new \Pluf\NoteBook\Book();
        $item = new \Pluf\NoteBook\Item();

        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($book));
        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($item));

        // crate 5 item
        $book->title = 'title xxx' . rand();
        $this->assertTrue($book->create());

        // 1
        $count = $book->getList([
            'count' => true
        ]);
        $this->assertEquals(1, $count);

        // 3
        // XXX: maso,
        $list = $book->getList([
            'view' => 'nonEmpty'
        ]);
        $this->assertEquals(0, count($list));

        // $list = $book->getList([
        // 'view' => 'empty'
        // ]);
        // $this->assertEquals(1, count($list));
    }

    /**
     *
     * @test
     */
    public function testGetOneItemByView()
    {
        $book = new \Pluf\NoteBook\Book();
        $item = new \Pluf\NoteBook\Item();

        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($book));
        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($item));

        // crate 5 item
        $book->title = 'title xxx' . rand();
        $this->assertTrue($book->create());

        // 1
        $itemOne = $book->getOne([
            // 'view' => 'empty',
            'filter' => [
                [
                    'title',
                    $book->title
                ]
            ]
        ]);
        $this->assertEquals($itemOne->title, $book->title);
    }

    /**
     *
     * @test
     */
    public function testGetCountFromModel()
    {
        $book = new \Pluf\NoteBook\Book();
        $item = new \Pluf\NoteBook\Item();

        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($book));
        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($item));

        // crate 5 item
        $book->title = 'title xxx' . rand();
        $this->assertTrue($book->create());

        $book->title = 'title xxx' . rand();
        $this->assertTrue($book->create());

        // 1
        $count = $book->getCount([
            'filter' => [
                [
                    'title',
                    $book->title
                ]
            ]
        ]);
        $this->assertEquals(1, $count);
    }

    /*
     * TODO:
     *
     * - create by id ..................... ok
     * - get .............................. ok
     * - getOne ........................... ok
     * - create ........................... ok
     * - count ............................ ok
     * - getList .......................... ok
     * - update
     * - delete
     * - setAssoc
     * - delAssoc
     *
     */
}

