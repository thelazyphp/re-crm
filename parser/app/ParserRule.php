<?php

namespace App;

class ParserRule
{
    /**
     * @var \Closure[]
     */
    protected $closures = [];

    /**
     * @param  \App\HtmlElement[]  $elements
     * @param  int|null  $index
     * @return \App\HtmlElement[]|\App\HtmlElement|null
     */
    protected static function takeElement($elements, $index = null)
    {
        if (is_null($index)) {
            return $elements;
        }

        if ($index < 0) {
            $index += count($elements);
        }

        return isset($elements[$index]) ? $elements[$index] : null;
    }

    /**
     * @param  mixed  $source
     * @return mixed
     */
    public function eval($source)
    {
        return array_reduce($this->closures, function ($result, $closure) {
            return call_user_func($closure, $result);
        }, $source);
    }

    /**
     * @param  mixed  $source
     * @return bool
     */
    public function empty($source)
    {
        return empty(
            $this->eval($source)
        );
    }

    /**
     * @param  mixed  $source
     * @return bool
     */
    public function filled($source)
    {
        return ! $this->empty($source);
    }

    /**
     * @param  int  $levels
     * @return $this
     */
    public function parent($levels = 1)
    {
        $this->closures[] = function ($result) use ($levels) {
            for ($level = 1; $level <= $levels; $level++) {
                if ($result instanceof HtmlElement) {
                    $result = $result->parentElement();
                } else {
                    break;
                }
            }

            return $result;
        };

        return $this;
    }

    /**
     * @return $this
     */
    public function children()
    {
        $this->closures[] = function ($result) {
            if ($result instanceof HtmlElement || $result instanceof HtmlDocument) {
                return $result->childElements();
            }

            return $result;
        };

        return $this;
    }

    /**
     * @param  int  $index
     * @return $this
     */
    public function child($index)
    {
        $this->closures[] = function ($result) use ($index) {
            if ($result instanceof HtmlElement || $result instanceof HtmlDocument) {
                return static::takeElement(
                    $result->childElements(), $index
                );
            }

            return $result;
        };

        return $this;
    }

    /**
     * @return $this
     */
    public function lastChild()
    {
        $this->closures[] = function ($result) {
            if ($result instanceof HtmlElement || $result instanceof HtmlDocument) {
                return $result->lastElementChild();
            }

            return $result;
        };

        return $this;
    }

    /**
     * @return $this
     */
    public function firstChild()
    {
        $this->closures[] = function ($result) {
            if ($result instanceof HtmlElement || $result instanceof HtmlDocument) {
                return $result->firstElementChild();
            }

            return $result;
        };

        return $this;
    }

    /**
     * @return $this
     */
    public function nextSibling()
    {
        $this->closures[] = function ($result) {
            if ($result instanceof HtmlElement || $result instanceof HtmlDocument) {
                return $result->nextElementSibling();
            }

            return $result;
        };

        return $this;
    }

    /**
     * @return $this
     */
    public function previousSibling()
    {
        $this->closures[] = function ($result) {
            if ($result instanceof HtmlElement || $result instanceof HtmlDocument) {
                return $result->previousElementSibling();
            }

            return $result;
        };

        return $this;
    }

    /**
     * @return $this
     */
    public function html()
    {
        $this->closures[] = function ($result) {
            if ($result instanceof HtmlElement || $result instanceof HtmlDocument) {
                return ($html = $result->saveHTML($result)) === false
                    ? ''
                    : trim($html);
            }

            return $result;
        };

        return $this;
    }

    /**
     * @return $this
     */
    public function text()
    {
        $this->closures[] = function ($result) {
            if ($result instanceof HtmlElement || $result instanceof HtmlDocument) {
                return trim($result->textContent);
            }

            return $result;
        };

        return $this;
    }

    /**
     * @param  string  $name
     * @param  mixed  $default
     * @return $this
     */
    public function attribute($name, $default = null)
    {
        $this->closures[] = function ($result) use ($name, $default) {
            if ($result instanceof HtmlElement) {
                $attribute = $result->getAttribute($name);

                if (empty($attribute)) {
                    return $result->hasAttribute($name)
                        ? true
                        : $default;
                }

                return $attribute;
            }

            return $result;
        };

        return $this;
    }

    /**
     * @param  string  $name
     * @param  mixed  $default
     * @return $this
     */
    public function attr($name, $default = null)
    {
        return $this->attribute($name, $default);
    }

    /**
     * @param  bool  $assoc
     * @param  int  $depth
     * @param  int  $options
     * @return $this
     */
    public function fromJson(
        $assoc = false,
        $depth = 512,
        $options = 0)
    {
        $this->closures[] = function ($result) use (
            $assoc,
            $depth,
            $options)
        {
            return json_decode(
                $result,
                $assoc,
                $depth,
                $options
            );
        };

        return $this;
    }

    /**
     * @param  int  $options
     * @param  int  $depth
     * @return $this
     */
    public function toJson($options = 0, $depth = 512)
    {
        $this->closures[] = function ($result) use ($options, $depth) {
            return json_encode(
                $result, $options, $depth
            );
        };

        return $this;
    }

    /**
     * @param  string|array|int|null  $key
     * @param  mixed  $default
     * @return $this
     */
    public function take($key, $default = null)
    {
        $this->closures[] = function ($result) use ($key, $default) {
            return data_get(
                $result,
                $key,
                $default
            );
        };

        return $this;
    }

    /**
     * @param  string  $pattern
     * @param  int  $limit
     * @param  int  $flags
     * @return $this
     */
    public function pregSplit(
        $pattern,
        $limit = -1,
        $flags = 0)
    {
        $this->text()->closures[] = function ($result) use (
            $pattern,
            $limit,
            $flags)
        {
            return preg_split(
                $pattern, $result, $limit, $flags
            );
        };

        return $this;
    }

    /**
     * @param  string  $delimiter
     * @param  null|int  $limit
     * @return $this
     */
    public function explode($delimiter, $limit = null)
    {
        $this->text()->closures[] = function ($result) use ($delimiter, $limit) {
            return explode(
                $delimiter, $result, $limit
            );
        };

        return $this;
    }

    /**
     * @param  string  $delimiter
     * @return $this
     */
    public function implode($delimiter)
    {
        $this->map(function () {
            return (new self)->text();
        })->closures[] = function ($result) use ($delimiter) {
            return implode($delimiter, $result);
        };

        return $this;
    }

    /**
     * @param  callable  $callback
     * @return $this
     */
    public function map(callable $callback)
    {
        $this->closures[] = function ($result) use ($callback) {
            $result = (array) $result;

            foreach ($result as $key => $value) {
                $val = call_user_func(
                    $callback, $value, $key
                );

                if ($val instanceof self) {
                    $val = $val->eval($value);
                }

                $result[$key] = $val;
            }

            return $result;
        };

        return $this;
    }

    /**
     * @param  callable|null  $callback
     * @param  int  $flag
     * @return $this
     */
    public function filter(?callable $callback = null, $flag = 0)
    {
        $this->closures[] = function ($result) use ($callback, $flag) {
            return array_filter(
                (array) $result, $callback, $flag
            );
        };

        return $this;
    }

    /**
     * @param  string  $pattern
     * @param  int  $group
     * @param  mixed  $default
     * @return $this
     */
    public function match(
        $pattern,
        $group = 0,
        $default = null)
    {
        $this->text()->closures[] = function ($result) use (
            $pattern,
            $group,
            $default)
        {
            if (preg_match($pattern, $result, $matches)) {
                if (isset($matches[$group])) {
                    return $matches[$group];
                }
            }

            return $default;
        };

        return $this;
    }

    /**
     * @param  string  $pattern
     * @param  int  $group
     * @param  mixed  $default
     * @return $this
     */
    public function matchAll(
        $pattern,
        $group = 0,
        $default = [])
    {
        $this->text()->closures[] = function ($result) use (
            $pattern,
            $group,
            $default)
        {
            if (preg_match_all($pattern, $result, $matches)) {
                if (isset($matches[$group])) {
                    return $matches[$group];
                }
            }

            return $default;
        };

        return $this;
    }

    /**
     * @param  string|string[]  $search
     * @param  string|string[]  $replace
     * @param  bool  $ignoreCase
     * @return $this
     */
    public function replace(
        $search,
        $replace,
        $ignoreCase = false)
    {
        $this->text()->closures[] = function ($result) use (
            $search,
            $replace,
            $ignoreCase)
        {
            return call_user_func(
                $ignoreCase ? 'str_ireplace' : 'str_replace', $search, $replace, $result
            );
        };

        return $this;
    }

    /**
     * @param  string|string[]  $pattern
     * @param  string|string[]  $replacement
     * @return $this
     */
    public function pregReplace($pattern, $replacement)
    {
        $this->text()->closures[] = function ($result) use ($pattern, $replacement) {
            return preg_replace(
                $pattern, $replacement, $result
            );
        };

        return $this;
    }

    /**
     * @param  string|string[]  $regex
     * @param  callable  $callback
     * @return $this
     */
    public function pregReplaceCallback($regex, callable $callback)
    {
        $this->text()->closures[] = function ($result) use ($regex, $callback) {
            return preg_replace_callback(
                $regex, $callback, $result
            );
        };

        return $this;
    }

    /**
     * @return $this
     */
    public function takeInteger()
    {
        return $this->match('/(-?\d+)/', 1);
    }

    /**
     * @return $this
     */
    public function takeFloat()
    {
        return $this->match('/(-?\d+[.,]\d+)/', 1);
    }

    /**
     * @return $this
     */
    public function takeNumeric()
    {
        return $this->match('/(-?\d+(?:[.,]\d+)?)/', 1);
    }

    /**
     * @return $this
     */
    public function takeDigits()
    {
        return $this->pregReplace('/\D/', '');
    }

    /**
     * @return $this
     */
    public function removeDigits()
    {
        return $this->pregReplace('/\d/', '');
    }

    /**
     * @return $this
     */
    public function removeSpaces()
    {
        return $this->pregReplace('/\s/', '');
    }

    /**
     * @return $this
     */
    public function normalizeSpaces()
    {
        return $this->pregReplace('/\s{2,}/', ' ');
    }

    /**
     * @return $this
     */
    public function removeHTMLEntities()
    {
        return $this->pregReplace('/&(?:[a-zA-Z]|(?:#\d+));/', '');
    }

    /**
     * @return $this
     */
    public function toTimestamp()
    {
        $this->text()->closures[] = function ($result) {
            return strtotime($result);
        };

        return $this;
    }

    /**
     * @param  string  $format
     * @return $this
     */
    public function toDateTime($format = 'Y-m-d H:i:s')
    {
        $this->toTimestamp()->closures[] = function ($result) use ($format) {
            return date($format, $result);
        };

        return $this;
    }

    /**
     * @return $this
     */
    public function toBool()
    {
        $this->text()->closures[] = function ($result) {
            return (bool) $result;
        };

        return $this;
    }

    /**
     * @return $this
     */
    public function toInt()
    {
        $this->text()->closures[] = function ($result) {
            return (int) $result;
        };

        return $this;
    }

    /**
     * @return $this
     */
    public function toFloat()
    {
        $this->text()->closures[] = function ($result) {
            return (float) $result;
        };

        return $this;
    }

    /**
     * @return $this
     */
    public function toLowerCase()
    {
        $this->text()->closures[] = function ($result) {
            return mb_strtolower($result);
        };

        return $this;
    }

    /**
     * @return $this
     */
    public function toUpperCase()
    {
        $this->text()->closures[] = function ($result) {
            return mb_strtoupper($result);
        };

        return $this;
    }

    /**
     * @param  string  $value
     * @return $this
     */
    public function append($value)
    {
        $this->text()->closures[] = function ($result) use ($value) {
            return $result.$value;
        };

        return $this;
    }

    /**
     * @param  string  $value
     * @return $this
     */
    public function prepend($value)
    {
        $this->text()->closures[] = function ($result) use ($value) {
            return $value.$result;
        };

        return $this;
    }

    /**
     * @param  string  $selector
     * @param  int|null  $index
     * @return $this
     */
    public function find($selector, $index = 0)
    {
        $this->closures[] = function ($result) use ($selector, $index) {
            if ($result instanceof HtmlElement || $result instanceof HtmlDocument) {
                return static::takeElement(
                    $result->querySelectorAll($selector), $index
                );
            }

            return $result;
        };

        return $this;
    }

    /**
     * @param  string  $selector
     * @return $this
     */
    public function findAll($selector)
    {
        return $this->find($selector, null);
    }

    /**
     * @param  string  $selector
     * @return $this
     */
    public function findLast($selector)
    {
        return $this->find($selector, -1);
    }

    /**
     * @param  string  $selector
     * @param  string  $value
     * @param  int|null  $index
     * @param  bool  $ignoreCase
     * @param  bool  $trimSpaces
     * @return $this
     */
    public function findWhereText(
        $selector,
        $value,
        $index = 0,
        $ignoreCase = false,
        $trimSpaces = true)
    {
        $this->closures[] = function ($result) use (
            $selector,
            $value,
            $index,
            $ignoreCase,
            $trimSpaces)
        {
            if ($result instanceof HtmlElement || $result instanceof HtmlDocument) {
                $elements = [];

                foreach ($result->querySelectorAll($selector) as $element) {
                    $text = $element->textContent;

                    if ($trimSpaces) {
                        $text = trim($text);
                    }

                    if (call_user_func($ignoreCase ? 'strcasecmp' : 'strcmp', $text, $value) === 0) {
                        $elements[] = $element;
                    }
                }

                return static::takeElement($elements, $index);
            }

            return $result;
        };

        return $this;
    }

    /**
     * @param  string  $selector
     * @param  string  $value
     * @param  bool  $ignoreCase
     * @param  bool  $trimSpaces
     * @return $this
     */
    public function findAllWhereText(
        $selector,
        $value,
        $ignoreCase = false,
        $trimSpaces = true)
    {
        return $this->findWhereText(
            $selector,
            $value,
            null,
            $ignoreCase,
            $trimSpaces
        );
    }

    /**
     * @param  string  $selector
     * @param  string  $value
     * @param  bool  $ignoreCase
     * @param  bool  $trimSpaces
     * @return $this
     */
    public function findLastWhereText(
        $selector,
        $value,
        $ignoreCase = false,
        $trimSpaces = true)
    {
        return $this->findWhereText(
            $selector,
            $value,
            -1,
            $ignoreCase,
            $trimSpaces
        );
    }

    /**
     * @param  string  $selector
     * @param  string  $pattern
     * @param  int|null  $index
     * @param  bool  $trimSpaces
     * @return $this
     */
    public function findWhereTextMatches(
        $selector,
        $pattern,
        $index = 0,
        $trimSpaces = true)
    {
        $this->closures[] = function ($result) use (
            $selector,
            $pattern,
            $index,
            $trimSpaces)
        {
            if ($result instanceof HtmlElement || $result instanceof HtmlDocument) {
                $elements = [];

                foreach ($result->querySelectorAll($selector) as $element) {
                    $text = $element->textContent;

                    if ($trimSpaces) {
                        $text = trim($text);
                    }

                    if (preg_match($pattern, $text)) {
                        $elements[] = $element;
                    }
                }

                return static::takeElement($elements, $index);
            }

            return $result;
        };

        return $this;
    }

    /**
     * @param  string  $selector
     * @param  string  $pattern
     * @param  bool  $trimSpaces
     * @return $this
     */
    public function findAllWhereTextMatches(
        $selector,
        $pattern,
        $trimSpaces = true)
    {
        return $this->findWhereTextMatches(
            $selector,
            $pattern,
            null,
            $trimSpaces
        );
    }

    /**
     * @param  string  $selector
     * @param  string  $pattern
     * @param  bool  $trimSpaces
     * @return $this
     */
    public function findLastWhereTextMatches(
        $selector,
        $pattern,
        $trimSpaces = true)
    {
        return $this->findWhereTextMatches(
            $selector,
            $pattern,
            -1,
            $trimSpaces
        );
    }

    /**
     * @param  string  $selector
     * @param  string  $value
     * @param  int|null  $index
     * @param  bool  $ignoreCase
     * @param  bool  $trimSpaces
     * @return $this
     */
    public function findWhereTextContains(
        $selector,
        $value,
        $index = 0,
        $ignoreCase = false,
        $trimSpaces = true)
    {
        $this->closures[] = function ($result) use (
            $selector,
            $value,
            $index,
            $ignoreCase,
            $trimSpaces)
        {
            if ($result instanceof HtmlElement || $result instanceof HtmlDocument) {
                $elements = [];

                foreach ($result->querySelectorAll($selector) as $element) {
                    $text = $element->textContent;

                    if ($trimSpaces) {
                        $text = trim($text);
                    }

                    if (call_user_func($ignoreCase ? 'mb_stripos' : 'mb_strpos', $text, $value) !== false) {
                        $elements[] = $element;
                    }
                }

                return static::takeElement($elements, $index);
            }

            return $result;
        };

        return $this;
    }

    /**
     * @param  string  $selector
     * @param  string  $value
     * @param  bool  $ignoreCase
     * @param  bool  $trimSpaces
     * @return $this
     */
    public function findAllWhereTextContains(
        $selector,
        $value,
        $ignoreCase = false,
        $trimSpaces = true)
    {
        return $this->findWhereTextContains(
            $selector,
            $value,
            null,
            $ignoreCase,
            $trimSpaces
        );
    }

    /**
     * @param  string  $selector
     * @param  string  $value
     * @param  bool  $ignoreCase
     * @param  bool  $trimSpaces
     * @return $this
     */
    public function findLastWhereTextContains(
        $selector,
        $value,
        $ignoreCase = false,
        $trimSpaces = true)
    {
        return $this->findWhereTextContains(
            $selector,
            $value,
            -1,
            $ignoreCase,
            $trimSpaces
        );
    }

    /**
     * @param  string  $selector
     * @param  string  $value
     * @param  int|null  $index
     * @param  bool  $ignoreCase
     * @param  bool  $trimSpaces
     * @return $this
     */
    public function findWhereTextEndsWith(
        $selector,
        $value,
        $index = 0,
        $ignoreCase = false,
        $trimSpaces = true)
    {
        $this->closures[] = function ($result) use (
            $selector,
            $value,
            $index,
            $ignoreCase,
            $trimSpaces)
        {
            if ($result instanceof HtmlElement || $result instanceof HtmlDocument) {
                $elements = [];

                foreach ($result->querySelectorAll($selector) as $element) {
                    $text = $element->textContent;

                    if ($trimSpaces) {
                        $text = trim($text);
                    }

                    $pos = mb_strlen($text) - mb_strlen($value);

                    if (call_user_func($ignoreCase ? 'mb_stripos' : 'mb_strpos', $text, $value) === $pos) {
                        $elements[] = $element;
                    }
                }

                return static::takeElement($elements, $index);
            }

            return $result;
        };

        return $this;
    }

    /**
     * @param  string  $selector
     * @param  string  $value
     * @param  bool  $ignoreCase
     * @param  bool  $trimSpaces
     * @return $this
     */
    public function findAllWhereTextEndsWith(
        $selector,
        $value,
        $ignoreCase = false,
        $trimSpaces = true)
    {
        return $this->findWhereTextEndsWith(
            $selector,
            $value,
            null,
            $ignoreCase,
            $trimSpaces
        );
    }

    /**
     * @param  string  $selector
     * @param  string  $value
     * @param  bool  $ignoreCase
     * @param  bool  $trimSpaces
     * @return $this
     */
    public function findLastWhereTextEndsWith(
        $selector,
        $value,
        $ignoreCase = false,
        $trimSpaces = true)
    {
        return $this->findWhereTextEndsWith(
            $selector,
            $value,
            -1,
            $ignoreCase,
            $trimSpaces
        );
    }

    /**
     * @param  string  $selector
     * @param  string  $value
     * @param  int|null  $index
     * @param  bool  $ignoreCase
     * @param  bool  $trimSpaces
     * @return $this
     */
    public function findWhereTextStartsWith(
        $selector,
        $value,
        $index = 0,
        $ignoreCase = false,
        $trimSpaces = true)
    {
        $this->closures[] = function ($result) use (
            $selector,
            $value,
            $index,
            $ignoreCase,
            $trimSpaces)
        {
            if ($result instanceof HtmlElement || $result instanceof HtmlDocument) {
                $elements = [];

                foreach ($result->querySelectorAll($selector) as $element) {
                    $text = $element->textContent;

                    if ($trimSpaces) {
                        $text = trim($text);
                    }

                    if (call_user_func($ignoreCase ? 'mb_stripos' : 'mb_strpos', $text, $value) === 0) {
                        $elements[] = $element;
                    }
                }

                return static::takeElement($elements, $index);
            }

            return $result;
        };

        return $this;
    }

    /**
     * @param  string  $selector
     * @param  string  $value
     * @param  bool  $ignoreCase
     * @param  bool  $trimSpaces
     * @return $this
     */
    public function findAllWhereTextStartsWith(
        $selector,
        $value,
        $ignoreCase = false,
        $trimSpaces = true)
    {
        return $this->findWhereTextStartsWith(
            $selector,
            $value,
            null,
            $ignoreCase,
            $trimSpaces
        );
    }

    /**
     * @param  string  $selector
     * @param  string  $value
     * @param  bool  $ignoreCase
     * @param  bool  $trimSpaces
     * @return $this
     */
    public function findLastWhereTextStartsWith(
        $selector,
        $value,
        $ignoreCase = false,
        $trimSpaces = true)
    {
        return $this->findWhereTextStartsWith(
            $selector,
            $value,
            -1,
            $ignoreCase,
            $trimSpaces
        );
    }
}
