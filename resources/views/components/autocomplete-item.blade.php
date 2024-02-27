@props([
    'unstyled' => false,
    'key',
    'value' => null,
    'active' => 'bg-blue-500',
    'inactive' => 'bg-white',
    'show',
    'statePath' => null,
    'isDisabled' => false,
])

@php
    if (!isset($key) || is_null($key)) {
        if (is_null($value)) {
            $key = '_x_autocomplete_empty';
        } else {
            $key = '_x_autocomplete_new';
        }
    }

    if (array_key_exists('show', get_defined_vars())) {
        $show = (bool) $show;
    } else {
        $show = true;
    }
@endphp

@if ($show && !$isDisabled)
    <li
        wire:click="dispatchFormEvent('selectValue::triggered', '{{ $statePath }}', '{{ $value }}')"
        wire:autocomplete-key="{{ $key }}"
        wire:autocomplete-value="{{ $value }}"
        x-on:click="selectItem()"
        x-on:mouseenter="focusKey(@js($key))"
        x-on:mouseleave="resetFocusedKey()"
        class="p-2 cursor-default">
        {{ $slot }}
    </li>
@else
    <li class="p-2 cursor-default">
        {{ $slot }}
    </li>
@endif
