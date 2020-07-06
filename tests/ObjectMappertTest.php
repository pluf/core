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
}

