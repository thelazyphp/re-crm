<?php

namespace App;

use Psr\Http\Message\UriInterface;

abstract class Parser
{
    /**
     * @var int
     */
    public $categoryId;

    /**
     * @var int
     */
    public $typeId;

    /**
     * @var int
     */
    public $sourceId;

    /**
     * @param  \Psr\Http\Message\UriInterface  $url
     * @param  \App\HtmlDocument  $html
     * @return array
     */
    abstract public function rules(UriInterface $url, HtmlDocument $html): array;
}
