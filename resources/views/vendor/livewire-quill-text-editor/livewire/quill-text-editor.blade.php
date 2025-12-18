<div wire:ignore>
    <div id="{{ $quillId }}" style="height: {{ $height ?? '250px' }};" class="quill-editor"></div>
</div>


@script
    <script>
        const quill = new Quill('#' + @js($quillId), {
            theme: @js($theme)
        });

        // Set initial content
        quill.root.innerHTML = $wire.get('value');

        // Sync ke Livewire
        quill.on('text-change', function() {
            let value = quill.root.innerHTML;
            @this.set('value', value);
        });
    </script>
@endscript
