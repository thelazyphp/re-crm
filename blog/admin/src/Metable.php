<?php

namespace Admin;

trait Metable
{
    /**
     * @var array
     */
    public $meta = [];

    /**
     * @param  array  $meta
     * @return $this
     */
    public function withMeta(array $meta)
    {
        $this->meta = array_merge($this->meta, $meta);

        return $this;
    }
}
