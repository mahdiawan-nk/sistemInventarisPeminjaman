<div>
    <x-form wire:submit.prevent="update" class="space-y-6 ">

        {{-- Dynamic Grid Form --}}
        <div class="grid grid-cols-1 gap-4">
            @foreach ($this->fillable() as $field)
                <div class="col-span-1">

                    <flux:field>
                        {{-- Label + Badge --}}
                        <flux:label badge="{{ $field['required'] ?? false ? 'Wajib' : 'Opsional' }}"
                            class="font-semibold">
                            {{ $field['label'] }}
                        </flux:label>

                        {{-- Select Input --}}
                        @if ($field['type'] === 'select')
                            <flux:select wire:model="{{ $field['name'] }}"
                                placeholder="Pilih {{ strtolower($field['label']) }}...">
                                <flux:select.option>
                                    Pilih {{ strtolower($field['label']) }}
                                </flux:select.option>
                                @foreach ($field['options'] as $option)
                                    <flux:select.option value="{{ $option['value'] }}">
                                        {{ $option['label'] }}
                                    </flux:select.option>
                                @endforeach
                            </flux:select>

                            {{-- Textarea --}}
                        @elseif ($field['type'] === 'textarea')
                            @if ($field['name'] == 'notes')
                                <div
                                    class="relative w-full rounded-lg border bg-white p-4 [&>svg]:absolute [&>svg]:text-foreground [&>svg]:left-4 [&>svg]:top-4 [&>svg+div]:translate-y-[-3px] [&:has(svg)]:pl-11 text-neutral-900">
                                    <div class="text-sm opacity-70">Jika Ada Perubahan Harap Tuliskan Notes</div>
                                </div>
                            @endif
                            <flux:textarea rows="3" wire:model="{{ $field['name'] }}"
                                placeholder="Masukkan {{ strtolower($field['label']) }}..." />

                            {{-- Input Default --}}
                        @else
                            <flux:input type="{{ $field['type'] }}" wire:model="{{ $field['name'] }}"
                                placeholder="Masukkan {{ strtolower($field['label']) }}..." />
                        @endif

                        {{-- Error Message --}}
                        <flux:error name="{{ $field['name'] }}" />
                    </flux:field>

                </div>
            @endforeach
        </div>

        {{-- Submit --}}
        <div class="pt-3 flex justify-end">
            <flux:button type="submit" variant="primary">Simpan data</flux:button>
            {{-- <x-button type="submit" variant="primary" class="px-6 py-2">
                Simpan Data
            </x-button> --}}
        </div>

    </x-form>
</div>
