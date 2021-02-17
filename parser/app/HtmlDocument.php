<?php

namespace App;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Symfony\Component\CssSelector\CssSelectorConverter;

class HtmlDocument extends DOMDocument
{
    /**
     * {@inheritDoc}
     */
    public function __construct($version = '', $encoding = '')
    {
        parent::__construct($version, $encoding);

        $this->registerNodeClass(
            DOMElement::class, HtmlElement::class
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->saveHTML() ?: '';
    }

    /**
     * {@inheritDoc}
     */
    public function loadHTML($source, $options = 0)
    {
        libxml_use_internal_errors(true);

        $this->preserveWhiteSpace = false;
        $this->formatOutput = true;

        $result = parent::loadHTML($source, $options);

        libxml_clear_errors();

        $this->normalizeDocument();

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function loadHTMLFile($filename, $options = 0)
    {
        libxml_use_internal_errors(true);

        $this->preserveWhiteSpace = false;
        $this->formatOutput = true;

        $result = parent::loadHTMLFile($filename, $options);

        libxml_clear_errors();

        $this->normalizeDocument();

        return $result;
    }

    /**
     * @param  string  $selector
     * @return \App\HtmlElement|null
     */
    public function querySelector($selector)
    {
        return count($elements = $this->querySelectorAll($selector)) == 0
            ? null
            : $elements[0];
    }

    /**
     * @param  string  $selector
     * @return \App\HtmlElement[]
     */
    public function querySelectorAll($selector)
    {
        $converter = new CssSelectorConverter();

        $path = new DOMXPath($this);

        $result = $path->query(
            $converter->toXPath($selector)
        );

        if ($result === false) {
            return [];
        }

        $elements = [];

        foreach ($result as $node) {
            if ($node->nodeType == XML_ELEMENT_NODE) {
                $elements[] = $node;
            }
        }

        return $elements;
    }

    /**
     * @return \App\HtmlElement[]
     */
    public function childElements()
    {
        $elements = [];

        foreach ($this->childNodes as $node) {
            if ($node->nodeType == XML_ELEMENT_NODE) {
                $elements[] = $node;
            }
        }

        return $elements;
    }

    /**
     * @return \App\HtmlElement|null
     */
    public function firstElementChild()
    {
        return count($elements = $this->childElements()) == 0
            ? null
            : $elements[0];
    }

    /**
     * @return \App\HtmlElement|null
     */
    public function lastElementChild()
    {
        return ($count = count($elements = $this->childElements())) == 0
            ? null
            : $elements[$count - 1];
    }
}
