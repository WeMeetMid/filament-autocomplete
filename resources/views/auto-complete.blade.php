@php
    $extraAlpineAttributes = $getExtraAlpineAttributes();
    $id = $getId();
    $isDisabled = $isDisabled();
    $isPrefixInline = $isPrefixInline();
    $isSuffixInline = $isSuffixInline();
    $prefixIcon = $getPrefixIcon();
    $prefixLabel = $getPrefixLabel();
    $suffixIcon = $getSuffixIcon();
    $suffixLabel = $getSuffixLabel();
    $statePath = $getStatePath();
    $hiddenInputId = $getHiddenInputId();
    $suggestions = $getSuggestions();
    $autoSelect = false;
    $minSearchLength = $getMinSearchLength();
@endphp

<x-dynamic-component 
    :component="$getFieldWrapperView()" 
    :field="$field" 
    :inline-label-vertical-alignment="\Filament\Support\Enums\VerticalAlignment::Center"
    >

    <div
        x-ignore
        ax-load
        ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-autocomplete', package: 'wemeetmid/filament-autocomplete') }}"
        x-data="autoCompleteFormComponent({
            key: @entangle($statePath),
            autoSelect: @js($autoSelect),
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            hiddenState: $wire.{{ $applyStateBindingModifiers("\$entangle('data.{$hiddenInputId}')") }},
        })"
        >

        <x-filament::input.wrapper 
            x-on:keydown.escape="escape($event)"
            x-on:click.outside="outside()"
            :disabled="$isDisabled" 
            :inline-prefix="$isPrefixInline" 
            :inline-suffix="$isSuffixInline" 
            :prefix="$prefixLabel" 
            :prefix-icon="$prefixIcon"
            :prefix-icon-color="$getPrefixIconColor()" 
            :suffix="$suffixLabel" 
            :suffix-icon="$suffixIcon" 
            :suffix-icon-color="$getSuffixIconColor()" 
            :valid="!$errors->has($statePath)"
            class="relative" 
            :attributes="\Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())"
            >

            <div
                class="w-full"
                x-data="{
                    inputValue: @entangle($statePath),
                    detachedInput: null,
                }" 
                x-init="valueProperty = @js((string) $statePath);
            
                    $nextTick(() => detachedInput = inputValue)
                
                    $watch('detachedInput', () => {
                    inputValue = detachedInput
                })" 
                x-modelable="detachedInput" 
                x-model="value"
                {{-- Shift tab must go before tab to ensure it fires first and flags can be set to disable tab --}} 
                x-on:keydown.shift.tab="pressShiftTab()" 
                x-on:keydown.tab="tab()"
                x-on:keydown.backspace="clearSelectedItem()" 
                x-on:keydown.arrow-up.prevent="focusPrevious()"
                x-on:keydown.arrow-down.prevent="focusNext()" 
                x-on:keydown.meta.arrow-up.prevent.stop="focusFirst()"
                x-on:keydown.meta.arrow-down.prevent.stop="focusLast()" 
                x-on:keydown.home.prevent="focusFirst()"
                x-on:keydown.end.prevent="focusLast()" 
                x-on:keydown.enter.stop="enter($event)"
                >

                <x-filament::input 
                    x-on:focus="inputFocus()"
                    :attributes="\Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())
                        ->merge($extraAlpineAttributes, escape: false)
                        ->merge(
                            [
                                'disabled' => $isDisabled,
                                'id' => $id,
                                'inlinePrefix' => $isPrefixInline && (count($prefixActions) || $prefixIcon || filled($prefixLabel)),
                                'inlineSuffix' => $isSuffixInline && (count($suffixActions) || $suffixIcon || filled($suffixLabel)),
                                'inputmode' => 'text',
                                'placeholder' => $getPlaceholder(),
                                'readonly' => $isReadOnly(),
                                'required' => $isRequired() && !$isConcealed,
                                'type' => 'text',
                                $applyStateBindingModifiers('wire:model') => $statePath,
                                'x-data' => count($extraAlpineAttributes) ? '{}' : null,
                            ],
                            escape: false,
                        )
                        " 
                    />

            </div>

            <ul 
                x-show="open" 
                x-cloak
                class="absolute z-10 w-full mt-2 rounded-lg overflow-auto bg-white text-sm shadow-lg ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
                >
                @if ($getState() >= $minSearchLength)
                    @if ($suggestions)
                        @foreach ($suggestions as $keyIndex => $suggestion)
                            <x-filament-autocomplete::autocomplete-item
                                :key="$keyIndex"
                                :value="$suggestion"
                                :statePath="$statePath"
                                {{-- active="bg-blue-500" --}}
                                {{-- inactive="bg-white" --}}
                                >
                                <span>{{ $suggestion }}</span>
                            </x-filament-autocomplete::autocomplete-item>
                        @endforeach
                    {{-- @else
                        <x-filament-autocomplete::autocomplete-item
                            :show="true"
                            :isDisabled="true"
                            :value="$getState()"
                            >
                            <span>Add New: {{ $getState() }}</span>
                        </x-filament-autocomplete::autocomplete-item> --}}
                    @endif
                @else
                    <x-filament-autocomplete::autocomplete-item
                        :show="true"
                        :isDisabled="true"
                        >
                        <span>To search, begin typing at least 3 characters...</span>
                    </x-filament-autocomplete::autocomplete-item>
                @endif
            </ul>

        </x-filament::input.wrapper>

    </div>

</x-dynamic-component>
