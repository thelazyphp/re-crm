<?php

namespace App;

use DOMElement;
use DOMXPath;
use Symfony\Component\CssSelector\CssSelectorConverter;

class HtmlElement extends DOMElement
{
    /**
     * @param  string  $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->hasAttribute($name);
    }

    /**
     * @param  string  $name
     * @return string
     */
    public function __get($name)
    {
        return $this->getAttribute($name);
    }

    /**
     * @param  string  $name
     * @return void
     */
    public function __unset($name)
    {
        $this->removeAttribute($name);
    }

    /**
     * @param  string  $name
     * @param  string  $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->setAttribute($name, $value);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if (is_null($this->ownerDocument)) {
            return '';
        }

        return $this->ownerDocument->saveHTML($this) ?: '';
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
        if (is_null($this->ownerDocument)) {
            return [];
        }

        $converter = new CssSelectorConverter();

        $path = new DOMXPath($this->ownerDocument);

        $result = $path->query(
            $converter->toXPath($selector), $this
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
     * @return \App\HtmlElement|null
     */
    public function parentElement()
    {
        $element = $this->parentNode;

        for (;; $element = $element->parentNode) {
            if (is_null($element) || $element->nodeType == XML_ELEMENT_NODE) {
                break;
            }
        }

        return $element;
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

    /**
     * @return \App\HtmlElement|null
     */
    public function nextElementSibling()
    {
        $element = $this->nextSibling;

        for (;; $element = $element->nextSibling) {
            if (is_null($element) || $element->nodeType == XML_ELEMENT_NODE) {
                break;
            }
        }

        return $element;
    }

    /**
     * @return \App\HtmlElement|null
     */
    public function previousElementSibling()
    {
        $element = $this->previousSibling;

        for (;; $element = $element->previousSibling) {
            if (is_null($element) || $element->nodeType == XML_ELEMENT_NODE) {
                break;
            }
        }

        return $element;
    }
}
