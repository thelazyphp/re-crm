<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class QueryFilter
{
    /**
     * @var string[]
     */
    protected $allowedFilters = [
        //
    ];

    /**
     * @var string[]
     */
    protected $allowedSorts = [
        //
    ];

    /**
     * @var string[]
     */
    protected $allowedIncludes = [
        //
    ];

    /**
     * @var array
     */
    protected $filterCallbacks = [];

    /**
     * @var array
     */
    protected $sortCallbacks = [];

    /**
     * @var array
     */
    protected $includeCallbacks = [];

    /**
     * @param  string[]  $filters
     * @return self
     */
    public function withAllowedFilters($filters)
    {
        $this->allowedFilters = $filters;
        return $this;
    }

    /**
     * @param  string[]  $sorts
     * @return self
     */
    public function withAllowedSorts($sorts)
    {
        $this->allowedSorts = $sorts;
        return $this;
    }

    /**
     * @param  string[]  $includes
     * @return self
     */
    public function withAllowedIncludes($includes)
    {
        $this->allowedIncludes = $includes;
        return $this;
    }

    /**
     * @param  string  $filter
     * @param  callable  $callback
     * @return self
     */
    public function withFilterCallback($filter, callable $callback)
    {
        $this->filterCallbacks[$filter] = $callback;
        return $this;
    }

    /**
     * @param  string  $sort
     * @param  callable  $callback
     * @return self
     */
    public function withSortCallback($sort, callable $callback)
    {
        $this->sortCallbacks[$sort] = $callback;
        return $this;
    }

    /**
     * @param  string  $include
     * @param  callable  $callback
     * @return self
     */
    public function withIncludeCallback($include, callable $callback)
    {
        $this->includeCallbacks[$include] = $callback;
        return $this;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filter(Builder $query, Request $request)
    {
        if ($request->filled('filter')) {
            foreach ($request->filter as $column => $value) {
                if (in_array($column, $this->allowedFilters)) {
                    if (isset($this->filterCallbacks[$column])) {
                        call_user_func(
                            $this->filterCallbacks[$column], $query, $column, $value
                        );
                    } else {
                        if (strpos($value, ',') !== false) {
                            $value = explode(',', $value);
                        }

                        if (($pos = strrpos($column, '.')) !== false) {
                            $relation = substr($column, 0, $pos);
                            $column = substr($column, $pos + 1);

                            $query->whereHas($relation, function (Builder $query) use ($column, $value) {
                                if (is_array($value)) {
                                    $query->whereIn(
                                        $column, $value
                                    );
                                } else {
                                    $query->where(
                                        $column, '=', $value
                                    );
                                }
                            });
                        } elseif (is_array($value)) {
                            $query->whereIn(
                                $column, $value
                            );
                        } else {
                            $query->where(
                                $column, '=', $value
                            );
                        }
                    }
                }
            }
        }

        if ($request->filled('sort')) {
            $sorts = explode(
                ',', $request->sort
            );

            foreach ($sorts as $column) {
                $order = 'asc';

                if (strpos($column, '-') === 0) {
                    $order = 'desc';
                    $column = substr($column, 1);
                }

                if (in_array($column, $this->allowedSorts)) {
                    if (isset($this->sortCallbacks[$column])) {
                        call_user_func(
                            $this->sortCallbacks[$column], $column, $order
                        );
                    } else {
                        $query->orderBy($column, $order);
                    }
                }
            }
        }

        if ($request->filled('include')) {
            $includes = explode(
                ',', $request->include
            );

            foreach ($includes as $include) {
                if (in_array($include, $this->allowedIncludes)) {
                    if (isset($this->includeCallbacks[$include])) {
                        call_user_func(
                            $this->includeCallbacks[$include], $include
                        );
                    } else {
                        $query->with($include);
                    }
                }
            }
        }

        return $query;
    }
}
