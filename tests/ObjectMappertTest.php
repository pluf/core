<?php
namespace Pluf\Test;

use Pluf;
use Pluf\NoteBook\Book;
use Pluf\HTTP\Request;
use Pluf\ObjectMapper;

class ObjectMappertTest extends PlufTestCase
{

    /**
     *
     * @beforeClass
     */
    public static function setUpTest()
    {
        Pluf::start(__DIR__ . '/conf/config.php');
    }

    /**
     *
     * @test
     */
    public function fillBookTest()
    {
        $book = new Book();

        $request = new Request('/test');
        $request->headers->setHeader('Content-Type', 'application/x-www-form-urlencoded');
        $request->REQUEST['title'] = $title = 'title' . rand();
        $request->method = 'POST';

        $this->assertTrue(ObjectMapper::getInstance($request)->hasMore());
        $book = ObjectMapper::getInstance($request)->next($book);
        $this->assertEquals($title, $book->title);
    }

    /**
     *
     * @test
     */
    public function fillBooksFromArray()
    {
        $books = [
            [
                "title" => "title" . rand(),
                "description" => "description" . rand()
            ],
            [
                "title" => "x" . rand(),
                "description" => "z" . rand()
            ]
        ];
        $mapper = ObjectMapper::getInstance($books);
        for ($i = 0; $i < count($books); $i ++) {
            $this->assertTrue($mapper->hasMore());
            $item = $mapper->next(Book::class);
            $this->assertEquals($books[$i]['title'], $item->title);
            $this->assertEquals($books[$i]['description'], $item->description);
        }
    }
}

