<?php

namespace KzykHys\FrontMatter;

use Symfony\Component\Yaml\Yaml;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class FrontMatter
{

    /**
     * @param $input
     *
     * @return int
     */
    public static function isValid($input)
    {
        return preg_match('/^-{3}\r?\n(.*)\r?\n-{3}\r?\n/s', $input) == 1;
    }

    /**
     * @param $input
     *
     * @return Document
     */
    public static function parse($input)
    {
        if (!preg_match('/^-{3}\r?\n(.*)\r?\n-{3}\r?\n/s', $input, $matches)) {
            return new Document($input);
        }

        return new Document(
            substr($input, strlen($matches[0])),
            Yaml::parse($matches[1])
        );
    }

    /**
     * @param Document $document
     *
     * @return string
     */
    public static function dump(Document $document, $format = "---\n%s\n---\n%s")
    {
        return sprintf(
            $format,
            trim(Yaml::dump($document->getConfig())),
            $document->getContent()
        );
    }

} 