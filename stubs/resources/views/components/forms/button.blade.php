<button {{ $attributes->merge(['type' => 'submit', 'class' => 'form-control']) }}>
    {{ $slot }}
</button>
