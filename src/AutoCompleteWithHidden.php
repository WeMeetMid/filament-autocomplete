<?php

namespace Meeftah\FilamentAutoComplete;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;

class AutoCompleteWithHidden
{
    public static function make(
        string $id,
        string $label = null,
        string $placeholder,
        string $hiddenInputId,
        string $model,
        string $searchAttribute,
    ): Group {
        $title = $label ?? $id;

        $autoComplete = AutoComplete::make($id)
            ->label($title)
            ->placeholder($placeholder)
            ->hiddenInputId($hiddenInputId)
            ->listModel($model)
            ->searchAttribute($searchAttribute);

        /** Input: "Slug Auto Update Disabled" (Hidden) */
        $hiddenInput = Hidden::make($hiddenInputId);

        return Group::make()
            ->schema([
                $autoComplete,
                $hiddenInput,
            ]);
    }
}
