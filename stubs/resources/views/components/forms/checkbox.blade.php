@props(['id' => 'checkbox1'])
<div class="custom-control custom-checkbox">
    <input type="checkbox" {!! $attributes->merge(['id' => $id, 'class' => 'checkbox']) !!}>
    <label class="custom-control-label" for="{{ $id }}">{{ $slot }}</label>
</div>
