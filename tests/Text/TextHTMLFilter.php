<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace Pluf\PlufTest\Text;

use PHPUnit\Framework\TestCase;
use Pluf\Bootstrap;
use Pluf\Text\HTML;

class TextHTMLFilter extends TestCase
{

    public $filter = '';

    /**
     *
     * @befor
     */
    public function setUpTest()
    {
        Bootstrap::start(__DIR__ . '/../conf/config.php');
        $this->filter = new HTML\Filter();
    }

    /**
     *
     * @test
     */
    public function testRunBatchOfTests()
    {
        // basics
        $this->filter_harness("", "");
        $this->filter_harness("hello", "hello");

        // balancing tags
        $this->filter_harness("<b>hello", "<b>hello</b>");
        $this->filter_harness("hello<b>", "hello");
        $this->filter_harness("hello<b>world", "hello<b>world</b>");
        $this->filter_harness("hello</b>", "hello");
        $this->filter_harness("hello<b/>", "hello");
        $this->filter_harness("hello<b/>world", "hello<b>world</b>");
        $this->filter_harness("<b><b><b>hello", "<b><b><b>hello</b></b></b>");
        $this->filter_harness("</b><b>", "");

        // end slashes
        $this->filter_harness('<img>', '<img />');
        $this->filter_harness('<img/>', '<img />');
        $this->filter_harness('<b/></b>', '');

        // balancing angle brakets

        $this->filter->always_make_tags = 1;
        $this->filter_harness('<img src="foo"', '<img src="foo" />');
        $this->filter_harness('b>', '');
        $this->filter_harness('b>hello', '<b>hello</b>');
        $this->filter_harness('<img src="foo"/', '<img src="foo" />');
        $this->filter_harness('>', '');
        $this->filter_harness('hello<b', 'hello');
        $this->filter_harness('b>foo', '<b>foo</b>');
        $this->filter_harness('><b', '');
        $this->filter_harness('b><', '');
        $this->filter_harness('><b>', '');
        $this->filter_harness('foo bar>', '');
        $this->filter_harness('foo>bar>baz', 'baz');
        $this->filter_harness('foo>bar', 'bar');
        $this->filter_harness('foo>bar>', '');
        $this->filter_harness('>foo>bar', 'bar');
        $this->filter_harness('>foo>bar>', '');

        $this->filter->always_make_tags = 0;
        $this->filter_harness('<img src="foo"', '&lt;img src=&quot;foo&quot;');
        $this->filter_harness('b>', 'b&gt;');
        $this->filter_harness('b>hello', 'b&gt;hello');
        $this->filter_harness('<img src="foo"/', '&lt;img src=&quot;foo&quot;/');
        $this->filter_harness('>', '&gt;');
        $this->filter_harness('hello<b', 'hello&lt;b');
        $this->filter_harness('b>foo', 'b&gt;foo');
        $this->filter_harness('><b', '&gt;&lt;b');
        $this->filter_harness('b><', 'b&gt;&lt;');
        $this->filter_harness('><b>', '&gt;');
        $this->filter_harness('foo bar>', 'foo bar&gt;');
        $this->filter_harness('foo>bar>baz', 'foo&gt;bar&gt;baz');
        $this->filter_harness('foo>bar', 'foo&gt;bar');
        $this->filter_harness('foo>bar>', 'foo&gt;bar&gt;');
        $this->filter_harness('>foo>bar', '&gt;foo&gt;bar');
        $this->filter_harness('>foo>bar>', '&gt;foo&gt;bar&gt;');

        // attributes
        $this->filter_harness('<img src=foo>', '<img src="foo" />');
        $this->filter_harness('<img asrc=foo>', '<img />');
        $this->filter_harness('<img src=test test>', '<img src="test" />');

        // non-allowed tags
        $this->filter_harness('<script>', '');
        $this->filter_harness('<script/>', '');
        $this->filter_harness('</script>', '');
        $this->filter_harness('<script woo=yay>', '');
        $this->filter_harness('<script woo="yay">', '');
        $this->filter_harness('<script woo="yay>', '');

        $this->filter->always_make_tags = 1;
        $this->filter_harness('<script', '');
        $this->filter_harness('<script woo="yay<b>', '');
        $this->filter_harness('<script woo="yay<b>hello', '<b>hello</b>');
        $this->filter_harness('<script<script>>', '');
        $this->filter_harness('<<script>script<script>>', 'script');
        $this->filter_harness('<<script><script>>', '');
        $this->filter_harness('<<script>script>>', '');
        $this->filter_harness('<<script<script>>', '');

        $this->filter->always_make_tags = 0;
        $this->filter_harness('<script', '&lt;script');
        $this->filter_harness('<script woo="yay<b>', '&lt;script woo=&quot;yay');
        $this->filter_harness('<script woo="yay<b>hello', '&lt;script woo=&quot;yay<b>hello</b>');
        $this->filter_harness('<script<script>>', '&lt;script&gt;');
        $this->filter_harness('<<script>script<script>>', '&lt;script&gt;');
        $this->filter_harness('<<script><script>>', '&lt;&gt;');
        $this->filter_harness('<<script>script>>', '&lt;script&gt;&gt;');
        $this->filter_harness('<<script<script>>', '&lt;&lt;script&gt;');

        // bad protocols
        $this->filter_harness('<a href="http://foo">bar</a>', '<a href="http://foo">bar</a>');
        $this->filter_harness('<a href="ftp://foo">bar</a>', '<a href="ftp://foo">bar</a>');
        $this->filter_harness('<a href="mailto:foo">bar</a>', '<a href="mailto:foo">bar</a>');
        $this->filter_harness('<a href="javascript:foo">bar</a>', '<a href="#foo">bar</a>');
        $this->filter_harness('<a href="java script:foo">bar</a>', '<a href="#foo">bar</a>');
        $this->filter_harness('<a href="java' . "\t" . 'script:foo">bar</a>', '<a href="#foo">bar</a>');
        $this->filter_harness('<a href="java' . "\n" . 'script:foo">bar</a>', '<a href="#foo">bar</a>');
        $this->filter_harness('<a href="java' . "\r" . 'script:foo">bar</a>', '<a href="#foo">bar</a>');
        $this->filter_harness('<a href="java' . chr(1) . 'script:foo">bar</a>', '<a href="#foo">bar</a>');
        $this->filter_harness('<a href="java' . chr(0) . 'script:foo">bar</a>', '<a href="#foo">bar</a>');
        $this->filter_harness('<a href="jscript:foo">bar</a>', '<a href="#foo">bar</a>');
        $this->filter_harness('<a href="vbscript:foo">bar</a>', '<a href="#foo">bar</a>');
        $this->filter_harness('<a href="view-source:foo">bar</a>', '<a href="#foo">bar</a>');
        $this->filter_harness('<a href="  javascript:foo">bar</a>', '<a href="#foo">bar</a>');
        $this->filter_harness('<a href="jAvAsCrIpT:foo">bar</a>', '<a href="#foo">bar</a>');

        // bad protocols with entities (semicolons)
        $this->filter_harness('<a href="&#106;&#97;&#118;&#97;&#115;&#99;&#114;&#105;&#112;&#116;&#58;foo">bar</a>', '<a href="#foo">bar</a>');
        $this->filter_harness('<a href="&#0000106;&#0000097;&#0000118;&#0000097;&#0000115;&#0000099;&#0000114;&#0000105;&#0000112;&#0000116;&#0000058;foo">bar</a>', '<a href="#foo">bar</a>');
        $this->filter_harness('<a href="&#x6A;&#x61;&#x76;&#x61;&#x73;&#x63;&#x72;&#x69;&#x70;&#x74;&#x3A;foo">bar</a>', '<a href="#foo">bar</a>');

        // bad protocols with entities (no semicolons)
        $this->filter_harness('<a href="&#106&#97&#118&#97&#115&#99&#114&#105&#112&#116&#58;foo">bar</a>', '<a href="#foo">bar</a>');
        $this->filter_harness('<a href="&#0000106&#0000097&#0000118&#0000097&#0000115&#0000099&#0000114&#0000105&#0000112&#0000116&#0000058foo">bar</a>', '<a href="#foo">bar</a>');
        $this->filter_harness('<a href="&#x6A&#x61&#x76&#x61&#x73&#x63&#x72&#x69&#x70&#x74&#x3A;foo">bar</a>', '<a href="#foo">bar</a>');

        // self-closing tags
        $this->filter_harness('<img src="a">', '<img src="a" />');
        $this->filter_harness('<img src="a">foo</img>', '<img src="a" />foo');
        $this->filter_harness('</img>', '');

        // typos
        $this->filter_harness('<b>test<b/>', '<b>test</b>');
        $this->filter_harness('<b/>test<b/>', '<b>test</b>');
        $this->filter_harness('<b/>test', '<b>test</b>');

        // case conversion
        $this->case_harness('hello world', 'hello world');
        $this->case_harness('Hello world', 'Hello world');
        $this->case_harness('Hello World', 'Hello World');
        $this->case_harness('HELLO World', 'HELLO World');
        $this->case_harness('HELLO WORLD', 'Hello world');
        $this->case_harness('<b>HELLO WORLD', '<b>Hello world');
        $this->case_harness('<B>HELLO WORLD', '<B>Hello world');
        $this->case_harness('HELLO. WORLD', 'Hello. World');
        $this->case_harness('HELLO<b> WORLD', 'Hello<b> World');
        $this->case_harness("DOESN'T", "Doesn't");
        $this->case_harness("COMMA, TEST", 'Comma, test');
        $this->case_harness("SEMICOLON; TEST", 'Semicolon; test');
        $this->case_harness("DASH - TEST", 'Dash - test');

        // comments
        $this->filter->strip_comments = 0;
        $this->filter_harness('hello <!-- foo --> world', 'hello <!-- foo --> world');
        $this->filter_harness('hello <!-- <foo --> world', 'hello <!-- &lt;foo --> world');
        $this->filter_harness('hello <!-- foo> --> world', 'hello <!-- foo&gt; --> world');
        $this->filter_harness('hello <!-- <foo> --> world', 'hello <!-- &lt;foo&gt; --> world');

        $this->filter->strip_comments = 1;
        $this->filter_harness('hello <!-- foo --> world', 'hello  world');
        $this->filter_harness('hello <!-- <foo --> world', 'hello  world');
        $this->filter_harness('hello <!-- foo> --> world', 'hello  world');
        $this->filter_harness('hello <!-- <foo> --> world', 'hello  world');

        // br - shouldn't get caught by the empty 'b' tag remover
        $this->filter->allowed['br'] = array();
        $this->filter->no_close[] = 'br';
        $this->filter_harness('foo<br>bar', 'foo<br />bar');
        $this->filter_harness('foo<br />bar', 'foo<br />bar');

        // stray quotes
        $this->filter_harness('foo"bar', 'foo&quot;bar');
        $this->filter_harness('foo"', 'foo&quot;');
        $this->filter_harness('"bar', '&quot;bar');
        $this->filter_harness('<a href="foo"bar">baz</a>', '<a href="foo">baz</a>');
        $this->filter_harness('<a href=foo"bar>baz</a>', '<a href="foo">baz</a>');

        // correct entities should not be touched
        $this->filter_harness('foo&amp;bar', 'foo&amp;bar');
        $this->filter_harness('foo&quot;bar', 'foo&quot;bar');
        $this->filter_harness('foo&lt;bar', 'foo&lt;bar');
        $this->filter_harness('foo&gt;bar', 'foo&gt;bar');

        // bare ampersands should be fixed up
        $this->filter_harness('foo&bar', 'foo&amp;bar');
        $this->filter_harness('foo&', 'foo&amp;');

        // numbered entities
        $this->filter->allow_numbered_entities = 1;
        $this->filter_harness('foo&#123;bar', 'foo&#123;bar');
        $this->filter_harness('&#123;bar', '&#123;bar');
        $this->filter_harness('foo&#123;', 'foo&#123;');

        $this->filter->allow_numbered_entities = 0;
        $this->filter_harness('foo&#123;bar', 'foo&amp;#123;bar');
        $this->filter_harness('&#123;bar', '&amp;#123;bar');
        $this->filter_harness('foo&#123;', 'foo&amp;#123;');

        // other entities
        $this->filter_harness('foo&bar;baz', 'foo&amp;bar;baz');
        $this->filter->allowed_entities[] = 'bar';
        $this->filter_harness('foo&bar;baz', 'foo&bar;baz');

        // entity decoder - '<'
        $entities = explode(' ', "%3c %3C &#60 &#0000060 &#60; &#0000060; &#x3c &#x000003c &#x3c; &#x000003c; &#X3c &#X000003c &#X3c; &#X000003c; &#x3C &#x000003C &#x3C; &#x000003C; &#X3C &#X000003C &#X3C; &#X000003C;");
        foreach ($entities as $entity) {
            $this->entity_harness($entity, '&lt;');
        }

        $this->entity_harness('%3c&#256;&#x100;', '&lt;&#256;&#256;');
        $this->entity_harness('%3c&#250;&#xFA;', '&lt;&#250;&#250;');
        $this->entity_harness('%3c%40%aa;', '&lt;@%aa');

        // character checks
        $this->filter_harness('\\', '\\');
        $this->filter_harness('/', '/');
        $this->filter_harness("'", "'");
        $this->filter_harness('a' . chr(0) . 'b', 'a' . chr(0) . 'b');
        $this->filter_harness('\\/\'!@#', '\\/\'!@#');
        $this->filter_harness('$foo', '$foo');

        // this test doesn't contain &"<> since they get changed
        $all_chars = ' !#$%\'()*+,-./0123456789:;=?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~';
        $this->filter_harness($all_chars, $all_chars);

        // single quoted entities
        $this->filter_harness("<img src=foo.jpg />", '<img src="foo.jpg" />');
        $this->filter_harness("<img src='foo.jpg' />", '<img src="foo.jpg" />');
        $this->filter_harness("<img src=\"foo.jpg\" />", '<img src="foo.jpg" />');

        // unbalanced quoted entities
        $this->filter_harness("<img src=\"foo.jpg />", '<img src="foo.jpg" />');
        $this->filter_harness("<img src='foo.jpg />", '<img src="foo.jpg" />');
        $this->filter_harness("<img src=foo.jpg\" />", '<img src="foo.jpg" />');
        $this->filter_harness("<img src=foo.jpg' />", '<img src="foo.jpg" />');

        // url escape sequences
        $this->filter_harness('<a href="woo.htm%22%20bar=%22#">foo</a>', '<a href="woo.htm&quot; bar=&quot;#">foo</a>');
        $this->filter_harness('<a href="woo.htm%22%3E%3C/a%3E%3Cscript%3E%3C/script%3E%3Ca%20href=%22#">foo</a>', '<a href="woo.htm&quot;&gt;&lt;/a&gt;&lt;script&gt;&lt;/script&gt;&lt;a href=&quot;#">foo</a>');
        $this->filter_harness('<a href="woo.htm%aa">foo</a>', '<a href="woo.htm%aa">foo</a>');
    }

    function filter_harness($in, $out)
    {
        $got = $this->filter->go($in);
        $this->assertEquals($out, $got);
    }

    function case_harness($in, $out)
    {
        $got = $this->filter->fix_case($in);
        $this->assertEquals($out, $got);
    }

    function entity_harness($in, $out)
    {
        $got = $this->filter->decode_entities($in);
        $this->assertEquals($out, $got);
    }
}
?>