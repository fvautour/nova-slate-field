<?php

namespace BBSLab\NovaSlateField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\FieldCollection;
use Laravel\Nova\Http\Requests\NovaRequest;

class Slate extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-slate-field';

    /**
     * Entry types for link resource feature
     *
     * @var array
     */
    public $entryTypes = [];

    public function withEntryTypes(array $types)
    {
        $this->entryTypes = $types;

        return $this;
    }

    /**
     * @return \BbsLab\NovaSlateField\ResourceMorphTo|null
     */
    public function entryField()
    {
        if (empty($this->entryTypes)) {
            return null;
        }

        return ResourceMorphTo::make(__('Entry'), $this->attribute.'_entry')
            ->types($this->entryTypes)
            ->searchable()
            ->nullable()
            ->withSubtitles();
    }

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'entry_field' => $this->entryField(),
        ]);
    }
}
