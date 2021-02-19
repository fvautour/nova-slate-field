<?php

namespace BBSLab\NovaSlateField;

use Laravel\Nova\Http\Controllers\MorphableController;
use Laravel\Nova\Http\Requests\NovaRequest;

trait CanLinkEntry
{
    public function availableFields(NovaRequest $request)
    {
        $fields = parent::availableFields($request);

        if (! $request->route()->controller instanceof MorphableController) {
            return $fields;
        }

        $slateFields = $fields->filter(function ($field) {
            return $field instanceof Slate;
        });

        foreach ($slateFields as $slateField) {
            /** @var \BBSLab\NovaSlateField\Slate $slateField */
            $fields->push($slateField->entryField());
        }

        return $fields;
    }
}
