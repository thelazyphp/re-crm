<?php

namespace Admin;

use Admin\Fields\Field;
use Admin\Fields\ID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use JsonSerializable;

/**
 * @method array indexFields(\Illuminate\Http\Request $request)
 * @method array detailFields(\Illuminate\Http\Request $request)
 * @method array createFields(\Illuminate\Http\Request $request)
 * @method array updateFields(\Illuminate\Http\Request $request)
 */
abstract class Resource implements JsonSerializable
{
    /**
     * @var string
     */
    public static $model;

    /**
     * @var string
     */
    public static $title = 'id';

    /**
     * @var bool
     */
    public static $showInNavigation = true;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $resource;

    /**
     * @return string
     */
    public static function name()
    {
        return Str::plural(
            Str::kebab(class_basename(get_called_class()))
        );
    }

    /**
     * @return string
     */
    public static function label()
    {
        return Str::title(
            Str::snake(class_basename(get_called_class()), ' ')
        );
    }

    /**
     * @return string
     */
    public static function pluralLabel()
    {
        return Str::plural(static::label());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function newModel()
    {
        return new static::$model;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return static
     */
    public static function forModel(Model $model)
    {
        return new static($model);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model|null  $resource
     */
    public function __construct(?Model $resource = null)
    {
        $this->resource = $resource ?? static::newModel();
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return [
            //
        ];
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->model()->{static::$title};
    }

    /**
     * @return string|null
     */
    public function subtitle()
    {
        return null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function model()
    {
        return $this->resource;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function createRules(Request $request)
    {
        return collect($this->getCreateFields($request, false))->mapWithKeys(function (Field $field) use ($request) {
            return $field->getCreateRules($request);
        })->all();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Facades\Validator
     */
    public function validatorForCreate(Request $request)
    {
        return Validator::make(
            $request->all(),
            $this->createRules($request)
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function updateRules(Request $request)
    {
        return collect($this->getUpdateFields($request, false))->mapWithKeys(function (Field $field) use ($request) {
            return $field->getUpdateRules($request);
        })->all();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Facades\Validator
     */
    public function validatorForUpdate(Request $request)
    {
        return Validator::make(
            $request->all(),
            $this->updateRules($request)
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $forUpdate
     * @return void
     */
    public function fill(Request $request, $forUpdate = false)
    {
        $validator = $forUpdate
            ? $this->validatorForUpdate($request)
            : $this->validatorForCreate($request);

        $validator->validate();

        $fields = $forUpdate
            ? $this->getUpdateFields($request, false)
            : $this->getCreateFields($request, false);

        $fields->each->fill(
            $request,
            $this->model()
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function serializeForIndex(Request $request)
    {
        $fields = $this->getIndexFields($request);

        return [
            'id' => $fields->whereInstanceOf(ID::class)->first() ?? ID::forModel($this->model()),
            'fields' => $fields->values()->all(),
            'title' => $this->title(),
            'subtitle' => $this->subtitle(),
        ];
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function serializeForDetail(Request $request)
    {
        $fields = $this->getDetailFields($request);

        return [
            'id' => $fields->whereInstanceOf(ID::class)->first() ?? ID::forModel($this->model()),
            'fields' => $fields->values()->all(),
            'title' => $this->title(),
            'subtitle' => $this->subtitle(),
        ];
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    abstract public function fields(Request $request): array;

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function getIndexFields(Request $request)
    {
        $method = method_exists($this, 'indexFields') ? 'indexFields' : 'fields';

        $fields = collect(
            call_user_func(
                [$this, $method], $request
            )
        );

        return $fields->filter(function (Field $field) use ($request) {
            return $field->isShowOnIndex($request);
        })->each(function (Field $field) {
            $field->resolve(
                $this->model(), true
            );
        });
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function getDetailFields(Request $request)
    {
        $method = method_exists($this, 'detailFields') ? 'detailFields' : 'fields';

        $fields = collect(
            call_user_func(
                [$this, $method], $request
            )
        );

        return $fields->filter(function (Field $field) use ($request) {
            return $field->isShowOnDetail($request, $this->model());
        })->each(function (Field $field) {
            $field->resolve(
                $this->model(), true
            );
        });
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $includeReadonly
     * @return \Illuminate\Support\Collection
     */
    public function getCreateFields(Request $request, $includeReadonly = true)
    {
        $method = method_exists($this, 'createFields') ? 'createFields' : 'fields';

        $fields = collect(
            call_user_func(
                [$this, $method], $request
            )
        );

        return $fields->filter(function (Field $field) use ($request, $includeReadonly) {
            return $field->isShowOnCreate($request) && $field->attribute != '__COMPUTED__';
        })->each(function (Field $field) {
            $field->resolve($this->model());
        });
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $includeReadonly
     * @return \Illuminate\Support\Collection
     */
    public function getUpdateFields(Request $request, $includeReadonly = true)
    {
        $method = method_exists($this, 'updateFields') ? 'updateFields' : 'fields';

        $fields = collect(
            call_user_func(
                [$this, $method], $request
            )
        );

        return $fields->filter(function (Field $field) use ($request, $includeReadonly) {
            return $field->isShowOnUpdate($request, $this->model()) && $field->attribute != '__COMPUTED__';
        })->each(function (Field $field) {
            $field->resolve($this->model());
        });
    }
}
