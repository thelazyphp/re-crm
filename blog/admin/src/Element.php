<?php

namespace Admin;

use Illuminate\Http\Request;

class Element
{
    /**
     * @var string|null
     */
    public $component;

    /**
     * @var array
     */
    public $meta = [];

    /**
     * @var \Closure|bool
     */
    protected $showOnIndex = true;

    /**
     * @var \Closure|bool
     */
    protected $showOnDetail = true;

    /**
     * @var \Closure|bool
     */
    protected $showOnCreate = true;

    /**
     * @var \Closure|bool
     */
    protected $showOnUpdate = true;

    /**
     * @param  string|null  $component
     * @param  array  $meta
     */
    public function __construct($component = null, array $meta = [])
    {
        $this->component = $component;
        $this->meta = $meta;
    }

    /**
     * @param  array  $meta
     * @return $this
     */
    public function meta(array $meta)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function showOnIndex($callback = true)
    {
        $this->showOnIndex = $callback;

        return $this;
    }

    /**
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function showOnDetail($callback = true)
    {
        $this->showOnDetail = $callback;

        return $this;
    }

    /**
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function showOnCreate($callback = true)
    {
        $this->showOnCreate = $callback;

        return $this;
    }

    /**
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function showOnUpdate($callback = true)
    {
        $this->showOnUpdate = $callback;

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyOnIndex()
    {
        $this->showOnIndex = true;
        $this->showOnDetail = $this->showOnCreate = $this->showOnUpdate = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyOnDetail()
    {
        $this->showOnDetail = true;
        $this->showOnIndex = $this->showOnCreate = $this->showOnUpdate = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyOnCreate()
    {
        $this->showOnCreate = true;
        $this->showOnIndex = $this->showOnDetail = $this->showOnUpdate = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyOnUpdate()
    {
        $this->showOnUpdate = true;
        $this->showOnIndex = $this->showOnDetail = $this->showOnCreate = false;

        return $this;
    }

    /**
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function hideFromIndex($callback = true)
    {
        $this->showOnIndex = is_callable($callback) ? function () use ($callback) {
            return ! call_user_func_array(
                $callback, func_get_args()
            );
        } : ! $callback;
    }

    /**
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function hideFromDetail($callback = true)
    {
        $this->showOnDetail = is_callable($callback) ? function () use ($callback) {
            return ! call_user_func_array(
                $callback, func_get_args()
            );
        } : ! $callback;
    }

    /**
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function hideFromCreate($callback = true)
    {
        $this->showOnCreate = is_callable($callback) ? function () use ($callback) {
            return ! call_user_func_array(
                $callback, func_get_args()
            );
        } : ! $callback;
    }

    /**
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function hideFromUpdate($callback = true)
    {
        $this->showOnUpdate = is_callable($callback) ? function () use ($callback) {
            return ! call_user_func_array(
                $callback, func_get_args()
            );
        } : ! $callback;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function isShowOnIndex(Request $request)
    {
        return is_callable($this->showOnIndex) ? call_user_func($this->showOnIndex, $request) : $this->showOnIndex;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function isShowOnDetail(Request $request)
    {
        return is_callable($this->showOnDetail) ? call_user_func($this->showOnDetail, $request) : $this->showOnDetail;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function isShowOnCreate(Request $request)
    {
        return is_callable($this->showOnCreate) ? call_user_func($this->showOnCreate, $request) : $this->showOnCreate;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function isShowOnUpdate(Request $request)
    {
        return is_callable($this->showOnUpdate) ? call_user_func($this->showOnUpdate, $request) : $this->showOnUpdate;
    }
}
