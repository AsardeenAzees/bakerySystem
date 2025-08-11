@props(['value' => null])

<label {{ $attributes->merge(['class' => 'form-label fw-semibold']) }}>
    @if ($value !== null)
        {{ $value }}
    @else
        {{ $slot }}
    @endif
</label>


