<?php

namespace KzykHys\FrontMatter;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class Document implements \ArrayAccess, \IteratorAggregate, \JsonSerializable
{

    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $content;

    /**
     * @param string $content
     * @param array  $config
     */
    public function __construct($content = '', $config = array())
    {
        $this->content = $content;
        $this->config  = $config;
    }

    /**
     * @param Document $parent
     *
     * @return $this
     */
    public function inherit(Document $parent)
    {
        $this->config = $this->mergeRecursive($parent->config, $this->config);

        return $this;
    }

    /**
     * @param Document $document
     *
     * @return $this
     */
    public function merge(Document $document)
    {
        $this->config = $this->mergeRecursive($this->config, $document->config);

        return $this;
    }

    /**
     * @param array $config
     *
     * @return Document
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param string $content
     *
     * @return Document
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->config[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->config[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->config);
    }

    /**
     * @param $itemA
     * @param $itemB
     *
     * @return array
     */
    private function mergeRecursive($itemA, $itemB)
    {
        if (!is_array($itemA) || !is_array($itemB)) {
            return $itemB;
        }

        foreach ($itemB as $key => $value) {
            if (is_string($key)) {
                $itemA[$key] = $this->mergeRecursive($itemA[$key], $value);
            } elseif (!in_array($value, $itemA)) {
                $itemA[] = $this->mergeRecursive($itemA[$key], $value);
            }
        }

        return $itemA;
    }


    /**
     * 
     * @return array
     */
    public function jsonSerialize() {
        return array_merge($this->config,[
            'content' => $this->content,
        ]);
    }

}