<?php
use KzykHys\FrontMatter\Document;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class DocumentTest extends PHPUnit_Framework_TestCase
{

    public function testDocument()
    {
        $document = new Document('<body>hello</body>', array('title' => 'test', 'category' => 'default'));

        $this->assertEquals('<body>hello</body>', $document->getContent());
        $this->assertEquals('<body>hello</body>', $document);
        $this->assertEquals(array('title' => 'test', 'category' => 'default'), $document->getConfig());

        $document
            ->setContent('test')
            ->setConfig(array('foo' => 'bar'));
    }

    public function testArrayAccess()
    {
        $document = new Document();
        $document['title'] = 'Title';
        $this->assertEquals('Title', $document['title']);

        unset($document['title']);

        $this->assertFalse(isset($document['title']));
    }

    public function testInherit()
    {
        $parent = new Document('test', array(
            'tags' => array('item1', 'item2'),
            'foo' => 'bar'
        ));

        $document = new Document('test', array('tags' => array('item3'), 'foo' => 'baz'));
        $document->inherit($parent);

        $this->assertEquals(array('tags' => array('item1', 'item2', 'item3'), 'foo' => 'baz'), $document->getConfig());
    }

    public function testMerge()
    {
        $child = new Document('test', array(
            'tags' => array('item1', 'item2'),
            'foo' => 'bar'
        ));

        $document = new Document('test', array('tags' => array('item3'), 'foo' => 'baz'));
        $document->merge($child);

        $this->assertEquals(array('tags' => array('item3', 'item1', 'item2'), 'foo' => 'bar'), $document->getConfig());
    }

    public function testIterator()
    {
        $document = new Document('test', array('foo' => 'bar', 'bar' => array(1,2)));

        $this->assertEquals(array('foo' => 'bar', 'bar' => array(1,2)), iterator_to_array($document));
    }

} 