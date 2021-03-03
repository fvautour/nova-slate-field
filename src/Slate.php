<?php

namespace BBSLab\NovaSlateField;

use Laravel\Nova\Fields\Field;

class Slate extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-slate-field';

    /**
     * Entry types for link resource feature.
     *
     * @var array
     */
    public $entryTypes = [];

    public $limit = 60;

    public function withEntryTypes(array $types)
    {
        $this->entryTypes = $types;

        return $this;
    }

    public function limit(int $limit)
    {
        $this->limit = $limit;

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
            'limit' => $this->limit,
        ]);
    }
}
