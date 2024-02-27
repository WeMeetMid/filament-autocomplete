<?php

namespace Meeftah\FilamentAutoSuggestion\Concerns;

use Closure;
use Filament\Support\Contracts\HasLabel as LabelInterface;
use Illuminate\Contracts\Support\Arrayable;

trait HasSuggestions
{
    /**
     * @var array<string | array<string>> | Arrayable | string | Closure | null
     */
    protected array | Arrayable | string | Closure | null $suggestions = null;

    /**
     * @param  array<string | array<string>> | Arrayable | string | Closure | null  $suggestions
     */
    public function suggestions(array | Arrayable | string | Closure | null $suggestions): static
    {
        $this->suggestions = $suggestions;

        return $this;
    }

    /**
     * @return array<string | array<string>>
     */
    public function getSuggestions(): array
    {
        $suggestions = $this->evaluate($this->suggestions) ?? [];

        $enum = $suggestions;
        if (
            is_string($enum) &&
            enum_exists($enum)
        ) {
            if (is_a($enum, LabelInterface::class, allow_string: true)) {
                return collect($enum::cases())
                    ->mapWithKeys(fn ($case) => [
                        ($case?->value ?? $case->name) => $case->getLabel() ?? $case->name,
                    ])
                    ->all();
            }

            return collect($enum::cases())
                ->mapWithKeys(fn ($case) => [
                    ($case?->value ?? $case->name) => $case->name,
                ])
                ->all();
        }

        if ($suggestions instanceof Arrayable) {
            $suggestions = $suggestions->toArray();
        }

        return $suggestions;
    }
}
