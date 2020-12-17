<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class QueryFilter
{
    /**
     * @var string
     */
    protected $searchParam = 'q';

    /**
     * @var string
     */
    protected $sortParam = 'sort';

    /**
     * @var string
     */
    protected $filterParam = 'filter';

    /**
     * @var string[]
     */
    protected $allowedSorts = [
        //
    ];

    /**
     * @var string[]
     */
    protected $allowedFilters = [
        //
    ];

    /**
     * @var callable|null
     */
    protected $searchCallback;

    /**
     * @var array
     */
    protected $sortCallbacks = [];

    /**
     * @var array
     */
    protected $filterCallbacks = [];

    /**
     * @param  string[]  $sorts
     * @return self
     */
    public function withAllowedSorts(array $sorts)
    {
        $this->allowedSorts = $sorts;
        return $this;
    }

    /**
     * @param  string[]  $filters
     * @return self
     */
    public function withAllowedFilters(array $filters)
    {
        $this->allowedFilters = $filters;
        return $this;
    }

    /**
     * @param  callable|null  $callback
     * @return self
     */
    public function withSearchCallback(?callable $callback)
    {
        $this->searchCallback = $callback;
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
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $query, Request $request)
    {
        if ($request->filled($this->searchParam)) {
            if (! is_null($this->searchCallback)) {
                call_user_func(
                    $this->searchCallback,
                    $query,
                    $request->input($this->searchParam)
                );
            }
        }

        if ($request->filled($this->sortParam)) {
            $sorts = explode(
                ',', $request->input($this->sortParam)
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
                            $this->sortCallbacks[$column], $query, $column, $order
                        );
                    } else {
                        $query->orderBy($column, $order);
                    }
                }
            }
        }

        if ($request->filled($this->filterParam)) {
            //
        }

        return $query;
    }
}
