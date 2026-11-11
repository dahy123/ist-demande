@props(['label' => null, 'name', 'id' => null, 'type' => 'text', 'value' => null, 'bag' => 'default'])

@php
    $error = $errors->getBag($bag)->first($name);
    $id ??= $name;
@endphp

<div>
    @if ($label)
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    @endif
    <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}"
        @if ($type !== 'password') value="{{ old($name, $value) }}" @endif
        {{ $attributes->class(['form-input', 'border-red-400 focus:border-red-500 focus:ring-red-100' => $error]) }}>
    @if ($error)
        <p class="form-error">{{ $error }}</p>
    @endif
</div>
