@props(['label' => null, 'name', 'type' => 'text', 'value' => null, 'bag' => 'default'])

@php($error = $errors->getBag($bag)->first($name))

<div>
    @if ($label)
        <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @endif
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}"
        @if ($type !== 'password') value="{{ old($name, $value) }}" @endif
        {{ $attributes->class(['form-input', 'border-red-400 focus:border-red-500 focus:ring-red-100' => $error]) }}>
    @if ($error)
        <p class="form-error">{{ $error }}</p>
    @endif
</div>
