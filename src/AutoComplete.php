<?php

namespace Meeftah\FilamentAutoComplete;

use Filament\Forms\Components\Concerns\CanBeReadOnly;
use Filament\Forms\Components\Concerns\HasAffixes;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Field;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Meeftah\FilamentAutoSuggestion\Concerns\HasSuggestions;

class AutoComplete extends Field
{
    use CanBeReadOnly;
    use HasAffixes;
    use HasExtraInputAttributes;
    use HasPlaceholder;
    use HasExtraAlpineAttributes;
    use HasSuggestions;

    protected string $view = 'filament-autocomplete::auto-complete';

    protected ?string $hiddenInputId = null;

    protected ?string $listModel = null;

    protected ?string $searchAttribute = null;

    protected int $minSearchLength = 3;

    protected ?string $selectedValue = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->live(debounce: 500);

        $this->registerListeners([
            'selectValue::triggered' => [
                function (AutoComplete $component, string $statePath, string $value) {
                    if ($statePath !== $component->getStatePath()) {
                        return;
                    }

                    $this->selectedValue = $value;

                    return $this;
                },
            ],
        ]);

        $this->afterStateUpdated(function ($state) {
            if ($state != $this->getSelectedValue() && \Str::length($state) >= $this->getMinSearchLength()) {
                $suggestions = app("App\Models\\" . $this->getListModel())::query()
                    ->where($this->getSearchAttribute(), 'LIKE', '%' . $state . '%')
                    ->get()
                    ->pluck($this->getSearchAttribute(), 'id')
                    ->toArray();

                $this->suggestions = $suggestions;
            }
        });
    }

    public function hiddenInputId(string $hiddenInputId): static
    {
        $this->hiddenInputId = $this->evaluate($hiddenInputId);

        return $this;
    }

    public function getHiddenInputId(): ?string
    {
        return $this->hiddenInputId;
    }

    public function listModel(string $listModel): static
    {
        $this->listModel = $listModel;

        return $this;
    }

    public function getListModel(): ?string
    {
        return $this->listModel;
    }

    public function searchAttribute(string $searchAttribute): static
    {
        $this->searchAttribute = $searchAttribute;

        return $this;
    }

    public function getSearchAttribute(): ?string
    {
        return $this->searchAttribute;
    }

    public function getSelectedValue(): ?string
    {
        return $this->selectedValue;
    }

    public function minSearchLength(int $minSearchLength): static
    {
        $this->minSearchLength = $minSearchLength;

        return $this;
    }

    public function getMinSearchLength(): ?int
    {
        return $this->minSearchLength;
    }
}
