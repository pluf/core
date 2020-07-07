<?php
namespace Pluf\Test\Data;

use PHPUnit\Framework\TestCase;
use Pluf\Data\ModelDescription;
use Pluf\Data\Query;
use Pluf;

/**
 *
 * Here is list of all \bootstrap\Data\Model supports.
 *
 * - create by id ..................... ok
 * - get .............................. ok
 * - getOne ........................... ok
 * - create ........................... ok
 * - count ............................ ok
 * - getList .......................... ok
 * - update ........................... ok
 * - delete ........................... ok
 * - get_xxx .......................... ok
 * - get_xxx_list ..................... ok
 * - setAssoc ......................... ok
 * - delAssoc ......................... ok
 * - set forign key ................... ok
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
            'db_dumper' => false,
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
            'view' => 'empty'
        ]);
        $this->assertEquals(1, count($list));

        $list = $book->getList([
            'view' => 'nonEmpty'
        ]);
        $this->assertEquals(0, count($list));
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

    /**
     *
     * @test
     */
    public function testUpdateModel()
    {
        $book = new \Pluf\NoteBook\Book();
        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($book));

        // crate 5 item
        $book->title = 'title xxx' . rand();
        $this->assertTrue($book->create());

        $book->title = 'title yyy' . rand();
        $this->assertTrue($book->update());

        $book2 = new \Pluf\NoteBook\Book($book->id);

        $this->assertEquals($book->title, $book2->title);
    }

    /**
     *
     * @test
     */
    public function testDeleteModel()
    {
        $book = new \Pluf\NoteBook\Book();
        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($book));

        // crate 5 item
        $book->title = 'title xxx' . rand();
        $this->assertTrue($book->create());
        $this->assertTrue($book->delete());

        $bookList = $book->getList();

        $this->assertEquals(0, count($bookList));
    }

    /**
     *
     * @test
     */
    public function gettingBookFromItem()
    {
        $book = new \Pluf\NoteBook\Book();
        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($book));

        $item = new \Pluf\NoteBook\Item();
        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($item));

        // crate 5 item
        $item->title = 'title xxx' . rand();
        $this->assertTrue($item->create());

        $book = $item->get_book();
        $this->assertNull($book);
    }

    /**
     *
     * @test
     */
    public function gettingListOfItemsFromBook()
    {
        $book = new \Pluf\NoteBook\Book();
        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($book));

        $item = new \Pluf\NoteBook\Item();
        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($item));

        // crate 5 item
        $book->title = 'title xxx' . rand();
        $this->assertTrue($book->create());

        $items = $book->get_items_list();
        $this->assertTrue(is_array($items));
        $this->assertEquals(0, count($items));
    }

    /**
     *
     * @test
     */
    public function gettingListOfTagsFromBook()
    {
        $book = new \Pluf\NoteBook\Book();
        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($book));

        $item = new \Pluf\NoteBook\Item();
        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($item));

        $tag = new \Pluf\NoteBook\Tag();
        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($tag));

        // crate 5 item
        $tag->title = 'tag xxx' . rand();
        $this->assertTrue($tag->create());

        $items = $tag->get_books_list();
        $this->assertTrue(is_array($items));
        $this->assertEquals(0, count($items));
    }

    /**
     *
     * @test
     */
    public function gettingSetAssocOfTagsFromBook()
    {
        $book = new \Pluf\NoteBook\Book();
        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($book));

        $item = new \Pluf\NoteBook\Item();
        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($item));

        $tag = new \Pluf\NoteBook\Tag();
        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($tag));

        // crate 5 item
        $tag->title = 'tag xxx' . rand();
        $this->assertTrue($tag->create());

        $items = $tag->get_books_list();
        $this->assertTrue(is_array($items));
        $this->assertEquals(0, count($items));

        $book = new \Pluf\NoteBook\Book();
        $book->title = 'book xxx' . rand();
        $this->assertTrue($book->create());

        $tag->setAssoc($book, 'books');
        $items = $tag->get_books_list();
        $this->assertTrue(is_array($items));
        $this->assertEquals(1, count($items));

        $book = new \Pluf\NoteBook\Book();
        $book->title = 'book xxx' . rand();
        $this->assertTrue($book->create());

        $tag->setAssoc($book, 'books');
        $items = $tag->get_books_list();
        $this->assertTrue(is_array($items));
        $this->assertEquals(2, count($items));

        $items = $book->get_tags_list();
        $this->assertTrue(is_array($items));
        $this->assertEquals(1, count($items));
    }

    /**
     *
     * @test
     */
    public function gettingDelAssocOfTagsFromBook()
    {
        $book = new \Pluf\NoteBook\Book();
        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($book));

        $item = new \Pluf\NoteBook\Item();
        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($item));

        $tag = new \Pluf\NoteBook\Tag();
        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($tag));

        // crate 5 item
        $tag->title = 'tag xxx' . rand();
        $this->assertTrue($tag->create());

        $book1 = new \Pluf\NoteBook\Book();
        $book1->title = 'book xxx' . rand();
        $this->assertTrue($book1->create());
        $tag->setAssoc($book1, 'books');

        $book2 = new \Pluf\NoteBook\Book();
        $book2->title = 'book xxx' . rand();
        $this->assertTrue($book2->create());
        $tag->setAssoc($book2, 'books');

        $items = $tag->get_books_list();
        $this->assertTrue(is_array($items));
        $this->assertEquals(2, count($items));

        $tag->delAssoc($book1, 'books');
        $items = $tag->get_books_list();
        $this->assertTrue(is_array($items));
        $this->assertEquals(1, count($items));

        $tag->delAssoc($book2, 'books');
        $items = $tag->get_books_list();
        $this->assertTrue(is_array($items));
        $this->assertEquals(0, count($items));
    }

    /**
     *
     * @test
     */
    public function settingItemsBookWithObjectAndId()
    {
        $book = new \Pluf\NoteBook\Book();
        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($book));

        $item = new \Pluf\NoteBook\Item();
        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($item));

        // crate 5 item
        $book->title = 'title xxx' . rand();
        $this->assertTrue($book->create());

        // crate 5 item
        $item->title = 'title xxx' . rand();
        $item->book = $book;
        $this->assertTrue($item->create());

        $items = $book->get_items_list();
        $this->assertTrue(is_array($items));
        $this->assertEquals(1, count($items));
    }
}

