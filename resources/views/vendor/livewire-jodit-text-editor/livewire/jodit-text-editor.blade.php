<div wire:ignore>
    <textarea id="{{ $joditId }}" class="dark:text-black">{!! $value !!}</textarea>
</div>

@script
    <script>
        const buttons = @json($buttons);
        const userTheme = @json($theme); // "dark", "light", "system"
        let resolvedTheme = userTheme;
        // console.log(userTheme)
        // Detect system theme
        if (userTheme === 'system') {
            resolvedTheme = window.matchMedia("(prefers-color-scheme: dark)").matches ?
                "dark" :
                "light";

            // Update when OS theme changes
            window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", e => {
                resolvedTheme = e.matches ? "dark" : "light";
                editor.setOptions({
                    theme: resolvedTheme
                });
            });
        }

        // Init Jodit
        const editor = Jodit.make('#' + @js($joditId), {
            autofocus: true,
            toolbarSticky: true,
            uploader: {
                insertImageAsBase64URI: true
            },
            toolbarButtonSize: "large",
            showCharsCounter: false,
            showWordsCounter: false,
            showXPathInStatusbar: false,
            defaultActionOnPaste: "insert_clear_html",
            buttons: buttons,
            theme: resolvedTheme,
        });

        // Sync ke Livewire
        document.getElementById(@js($joditId)).addEventListener('change', function() {
            @this.set('value', this.value);
        });

        // Update dari Livewire
        window.addEventListener('update-jodit-content', (event) => {
            if (Array.isArray(event.detail) && event.detail.length === 2) {
                const [targetId, newContent] = event.detail;
                if (targetId === @js($identifier)) {
                    editor.value = newContent;
                }
            } else {
                editor.value = event.detail[0];
            }
        });
    </script>
@endscript
