<?php

use KzykHys\FrontMatter\Document;
use KzykHys\FrontMatter\FrontMatter;

class FrontMatterTest extends PHPUnit_Framework_TestCase
{

    public function testParse()
    {
        $content  = file_get_contents(__DIR__ . '/Resources/markdown.md');
        $document = FrontMatter::parse($content);

        $this->assertEquals(array(
            'title' => 'Sample Post',
            'category' => 'technology',
            'tags' => array('PHP', 'Yaml', 'FrontMatter'),
            'vars' => array(
                'pingback' => 'http://example.com/pingback?id={{ post.id }}',
                'description' => null
            )
        ), $document->getConfig());

        $this->assertEquals('
Subtitle
--------

### Subtitle

Lorem Ipsum ...', $document->getContent());
    }

    public function testDump()
    {
        $document = new Document('<body>Hello</body>', array('title' => 'test', 'layout' => 'layout.html'));

        $dump = FrontMatter::dump($document);

        $this->assertEquals('---
title: test
layout: layout.html
---
<body>Hello</body>', $dump);
    }

    public function testInvalidInput()
    {
        $content  = file_get_contents(__DIR__ . '/Resources/invalid.html');
        $document = FrontMatter::parse($content);

        $this->assertEquals(array(), $document->getConfig());
        $this->assertEquals($content, $document->getContent());
    }

    public function testIsValid()
    {
        $content = file_get_contents(__DIR__ . '/Resources/markdown.md');
        $this->assertTrue(FrontMatter::isValid($content));

        $content = file_get_contents(__DIR__ . '/Resources/invalid.html');
        $this->assertFalse(FrontMatter::isValid($content));
    }

} 