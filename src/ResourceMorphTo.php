<?php

namespace BBSLab\NovaSlateField;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Nova\Contracts\RelatableField;
use Laravel\Nova\Fields\DeterminesIfCreateRelationCanBeShown;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Searchable;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\Query\Builder;
use Laravel\Nova\Resource;
use Laravel\Nova\Rules\Relatable;
use Laravel\Nova\TrashedStatus;

class ResourceMorphTo extends Field implements RelatableField
{
    use DeterminesIfCreateRelationCanBeShown, Searchable;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'resource-morph-to-field';

    /**
     * The class name of the related resource.
     *
     * @var string
     */
    public $resourceClass;

    /**
     * The URI key of the related resource.
     *
     * @var string
     */
    public $resourceName;

    /**
     * The key of the related Eloquent model.
     *
     * @var string
     */
    public $morphToId;

    /**
     * The type of the related Eloquent model.
     *
     * @var string
     */
    public $morphToType;

    /**
     * The types of resources that may be polymorphically related to this resource.
     *
     * @var array
     */
    public $morphToTypes = [];

    /**
     * The column that should be displayed for the field.
     *
     * @var \Closure|array
     */
    public $display;

    /**
     * Indicates if the related resource can be viewed.
     *
     * @var bool
     */
    public $viewable = true;

    /**
     * Indicates whether the field should display the "With Trashed" option.
     *
     * @var bool
     */
    public $displaysWithTrashed = true;

    /**
     * The default related class value for the field.
     *
     * @var Closure|string
     */
    public $defaultResourceCallable;

    /**
     * Resolve the field's value.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function resolve($resource, $attribute = null)
    {
        [$this->morphToId, $this->morphToType] = [
            $resource->{$this->attribute},
            $resource->{$this->attribute.'_type'},
        ];

        $value = $this->getValue();

        if ($resource->relationLoaded($this->attribute)) {
            $value = $resource->getRelation($this->attribute);
        }

        if ($resourceClass = $this->resolveResourceClass($value)) {
            $this->resourceName = $resourceClass::uriKey();
        }

        if ($value && ! empty($this->resourceClass)) {
            $resource = new $this->resourceClass($value);

            $this->value = $this->formatDisplayValue(
                $value, Nova::resourceForModel($value)
            );

            $this->viewable = $this->viewable
                && $resource->authorizedToView(request());
        }
    }

    /**
     * Retrieve model value.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function getValue()
    {
        if (empty($this->morphToId)) {
            return null;
        }

        if (empty($this->morphToType) || ! class_exists($this->morphToType)) {
            return null;
        }

        if (! is_subclass_of($this->morphToType, Model::class)) {
            return null;
        }

        return $this->morphToType::query()->withoutGlobalScopes()->find($this->morphToId);
    }

    /**
     * Resolve the field's value for display.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function resolveForDisplay($resource, $attribute = null)
    {
        $this->resolve($resource, $attribute);
    }

    /**
     * Resolve the resource class for the field.
     *
     * @param  \Illuminate\Database\Eloquent\Model
     * @return string|null
     */
    protected function resolveResourceClass($model)
    {
        return $this->resourceClass = Nova::resourceForModel($model);
    }

    /**
     * Get the validation rules for this field.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function getRules(NovaRequest $request)
    {
        $possibleTypes = collect($this->morphToTypes)->map->value->values();

        return array_merge_recursive(parent::getRules($request), [
            $this->attribute.'_type' => [$this->nullable ? 'nullable' : 'required', 'in:'.$possibleTypes->implode(',')],
            $this->attribute => array_filter([$this->nullable ? 'nullable' : 'required', $this->getRelatableRule($request)]),
        ]);
    }

    /**
     * Get the validation rule to verify that the selected model is relatable.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Laravel\Nova\Rules\Relatable|null
     */
    protected function getRelatableRule(NovaRequest $request)
    {
        if ($relatedResource = Nova::resourceForKey($request->{$this->attribute.'_type'})) {
            return new Relatable($request, $this->buildMorphableQuery(
                $request, $relatedResource, $request->{$this->attribute.'_trashed'} === 'true'
            )->toBase());
        }
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  object  $model
     * @return void
     */
    public function fill(NovaRequest $request, $model)
    {
        parent::fillInto($request, $model, $this->attribute);
        parent::fillInto($request, $model, $this->attribute.'_type');
    }

    /**
     * Build an morphable query for the field.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  string  $relatedResource
     * @param  bool  $withTrashed
     * @return \Laravel\Nova\Query\Builder
     */
    public function buildMorphableQuery(NovaRequest $request, $relatedResource, $withTrashed = false)
    {
        $model = $relatedResource::newModel();

        $query = new Builder($relatedResource);

        $request->first === 'true'
                        ? $query->whereKey($model->newQueryWithoutScopes(), $request->current)
                        : $query->search(
                                $request, $model->newQuery(), $request->search,
                                [], [], TrashedStatus::fromBoolean($withTrashed)
                          );

        return $query->tap(function ($query) use ($request, $relatedResource, $model) {
            forward_static_call(
                $this->morphableQueryCallable($request, $relatedResource, $model),
                $request, $query, $this
            );
        });
    }

    /**
     * Get the morphable query method name.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  string  $relatedResource
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return array
     */
    protected function morphableQueryCallable(NovaRequest $request, $relatedResource, $model)
    {
        return ($method = $this->morphableQueryMethod($request, $model))
                    ? [$request->resource(), $method]
                    : [$relatedResource, 'relatableQuery'];
    }

    /**
     * Get the morphable query method name.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return string
     */
    protected function morphableQueryMethod(NovaRequest $request, $model)
    {
        $method = 'relatable'.Str::plural(class_basename($model));

        return method_exists($request->resource(), $method) ? $method : null;
    }

    /**
     * Format the given morphable resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  mixed  $resource
     * @param  string  $relatedResource
     * @return array
     */
    public function formatMorphableResource(NovaRequest $request, $resource, $relatedResource)
    {
        return array_filter([
            'avatar' => $resource->resolveAvatarUrl($request),
            'display' => $this->formatDisplayValue($resource, $relatedResource),
            'subtitle' => $resource->subtitle(),
            'value' => $resource->getKey(),
        ]);
    }

    /**
     * Format the associatable display value.
     *
     * @param  mixed  $resource
     * @param  string  $relatedResource
     * @return string
     */
    protected function formatDisplayValue($resource, $relatedResource)
    {
        if (! $resource instanceof Resource) {
            $resource = Nova::newResourceFromModel($resource);
        }

        if ($display = $this->displayFor($relatedResource)) {
            return call_user_func($display, $resource);
        }

        return (string) $resource->title();
    }

    /**
     * Set the types of resources that may be related to the resource.
     *
     * @param  array  $types
     * @return $this
     */
    public function types(array $types)
    {
        $this->morphToTypes = collect($types)->map(function ($display, $key) {
            return [
                'type' => is_numeric($key) ? $display : $key,
                'singularLabel' => is_numeric($key) ? $display::singularLabel() : $key::singularLabel(),
                'display' => (is_string($display) && is_numeric($key)) ? $display::singularLabel() : $display,
                'value' => is_numeric($key) ? $display::uriKey() : $key::uriKey(),
            ];
        })->values()->all();

        return $this;
    }

    /**
     * Set the column that should be displayed for the field.
     *
     * @param  \Closure|array|string  $display
     * @return $this
     */
    public function display($display)
    {
        if (is_array($display)) {
            $this->display = collect($display)->mapWithKeys(function ($display, $type) {
                return [$type => $this->ensureDisplayerIsClosure($display)];
            })->all();
        } else {
            $this->display = $this->ensureDisplayerIsClosure($display);
        }

        return $this;
    }

    /**
     * Ensure the given displayer is a Closure.
     *
     * @param  \Closure|string  $display
     * @return \Closure
     */
    protected function ensureDisplayerIsClosure($display)
    {
        return $display instanceof Closure
                    ? $display
                    : function ($resource) use ($display) {
                        return $resource->{$display};
                    };
    }

    /**
     * Get the column that should be displayed for a given type.
     *
     * @param  string  $type
     * @return \Closure|null
     */
    public function displayFor($type)
    {
        if (is_array($this->display) && $type) {
            return $this->display[$type] ?? null;
        }

        return $this->display;
    }

    /**
     * Specify if the related resource can be viewed.
     *
     * @param  bool  $value
     * @return $this
     */
    public function viewable($value = true)
    {
        $this->viewable = $value;

        return $this;
    }

    /**
     * hides the "With Trashed" option.
     *
     * @return $this
     */
    public function withoutTrashed()
    {
        $this->displaysWithTrashed = false;

        return $this;
    }

    /**
     * Set the default relation resource class to be selected.
     *
     * @param \Closure|string $resourceClass
     * @return $this
     */
    public function defaultResource($resourceClass)
    {
        $this->defaultResourceCallable = $resourceClass;

        return $this;
    }

    /**
     * Resolve the default resource class for the field.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return string|void
     */
    protected function resolveDefaultResource(NovaRequest $request)
    {
        if ($request->isCreateOrAttachRequest() || $request->isResourceIndexRequest() || $request->isActionRequest()) {
            if (is_null($this->value) && $this->defaultResourceCallable instanceof Closure) {
                $class = call_user_func($this->defaultResourceCallable, $request);
            } else {
                $class = $this->defaultResourceCallable;
            }

            if (class_exists($class)) {
                return $class::uriKey();
            }
        }
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $resourceClass = $this->resourceClass;

        return with(app(NovaRequest::class), function ($request) use ($resourceClass) {
            return array_merge([
                'debounce' => $this->debounce,
                'morphToId' => $this->morphToId,
                'morphToType' => $this->morphToType,
                'morphToTypes' => $this->morphToTypes,
                'resourceLabel' => $resourceClass ? $resourceClass::singularLabel() : null,
                'resourceName' => $this->resourceName,
                'searchable' => $this->searchable,
                'withSubtitles' => $this->withSubtitles,
                'showCreateRelationButton' => $this->createRelationShouldBeShown($request),
                'displaysWithTrashed' => $this->displaysWithTrashed,
                'viewable' => $this->viewable,
                'defaultResource' => $this->resolveDefaultResource($request),
            ], parent::jsonSerialize());
        });
    }
}
