<div>
    <x-form wire:submit.prevent="store" class="space-y-6 px-2">

        {{-- Dynamic Grid Form --}}
        <div class="grid grid-cols-4 gap-4">
            @foreach ($this->fillable() as $rowStart => $field)
                <div class="{{ $field['col_span'] === 4 ? 'col-span-full' : 'col-span-' . $field['col_span'] }}">

                    <flux:field>
                        {{-- Label + Badge --}}
                        <flux:label badge="{{ $field['required'] ?? false ? 'Wajib' : 'Opsional' }}"
                            class="font-semibold">
                            {{ $field['label'] }}
                        </flux:label>

                        {{-- Select Input --}}
                        @if ($field['type'] === 'select')
                            <flux:select wire:model.change="{{ $field['name'] }}"
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
                            <flux:textarea rows="3" wire:model="{{ $field['name'] }}"
                                placeholder="Masukkan {{ strtolower($field['label']) }}..." />

                            {{-- Input Default --}}
                        @else
                            <flux:input type="{{ $field['type'] }}" wire:model="{{ $field['name'] }}"
                                placeholder="Masukkan {{ strtolower($field['label']) }}..."
                                :readonly="$field['readonly']" />
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
        </div>

    </x-form>
</div>
