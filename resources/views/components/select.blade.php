{{-- options : tableau [valeur => libellé] --}}
@props(['label' => null, 'name', 'options' => [], 'value' => null, 'placeholder' => null, 'bag' => 'default'])

@php
    $error = $errors->getBag($bag)->first($name);
    $selected = (string) old($name, $value instanceof \BackedEnum ? $value->value : $value);
@endphp

<div>
    @if ($label)
        <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @endif
    <select id="{{ $name }}" name="{{ $name }}"
        {{ $attributes->class(['form-input', 'border-red-400 focus:border-red-500 focus:ring-red-100' => $error]) }}>
        @if ($placeholder !== null)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected($selected === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
    @if ($error)
        <p class="form-error">{{ $error }}</p>
    @endif
</div>
