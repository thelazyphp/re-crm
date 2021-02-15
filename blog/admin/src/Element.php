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
     */
    public function __construct($component = null)
    {
        $this->component = $component;
    }

    /**
     * @param  \Closure|bool  $showOnIndex
     * @return $this
     */
    public function showOnIndex($showOnIndex = true)
    {
        $this->showOnIndex = $showOnIndex;

        return $this;
    }

    /**
     * @param  \Closure|bool  $showOnDetail
     * @return $this
     */
    public function showOnDetail($showOnDetail = true)
    {
        $this->showOnDetail = $showOnDetail;

        return $this;
    }

    /**
     * @param  \Closure|bool  $showOnCreate
     * @return $this
     */
    public function showOnCreate($showOnCreate = true)
    {
        $this->showOnCreate = $showOnCreate;

        return $this;
    }

    /**
     * @param  \Closure|bool  $showOnUpdate
     * @return $this
     */
    public function showOnUpdate($showOnUpdate = true)
    {
        $this->showOnUpdate = $showOnUpdate;

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
     * @param  \Closure|bool  $hideFromIndex
     * @return $this
     */
    public function hideFromIndex($hideFromIndex = true)
    {
        $this->showOnIndex = is_callable($hideFromIndex) ? function () use ($hideFromIndex) {
            return ! call_user_func_array(
                $hideFromIndex, func_get_args()
            );
        } : ! $hideFromIndex;

        return $this;
    }

    /**
     * @param  \Closure|bool  $hideFromDetail
     * @return $this
     */
    public function hideFromDetail($hideFromDetail = true)
    {
        $this->showOnDetail = is_callable($hideFromDetail) ? function () use ($hideFromDetail) {
            return ! call_user_func_array(
                $hideFromDetail, func_get_args()
            );
        } : ! $hideFromDetail;

        return $this;
    }

    /**
     * @param  \Closure|bool  $hideFromCreate
     * @return $this
     */
    public function hideFromCreate($hideFromCreate = true)
    {
        $this->showOnCreate = is_callable($hideFromCreate) ? function () use ($hideFromCreate) {
            return ! call_user_func_array(
                $hideFromCreate, func_get_args()
            );
        } : ! $hideFromCreate;

        return $this;
    }

    /**
     * @param  \Closure|bool  $hideFromUpdate
     * @return $this
     */
    public function hideFromUpdate($hideFromUpdate = true)
    {
        $this->showOnUpdate = is_callable($hideFromUpdate) ? function () use ($hideFromUpdate) {
            return ! call_user_func_array(
                $hideFromUpdate, func_get_args()
            );
        } : ! $hideFromUpdate;

        return $this;
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
