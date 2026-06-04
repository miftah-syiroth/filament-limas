<x-filament-widgets::widget class="fi-wi-barcode-scanner">
    <x-filament::section>
        <x-filament::input.wrapper :inline-prefix="true">
            <x-slot name="prefix">
                <x-filament::icon-button
                    tooltip="Scan barcode"
                    type="button"
                    size="lg"
                    icon="heroicon-o-camera"
                    x-on:click="startCamera()" />
            </x-slot>

            <x-filament::input
                type="text"
                wire:model.live.debounce.500ms="serial_number"
                maxlength="8"
                inputmode="text"
                autocomplete="off"
                :placeholder="__('barcode_scanner.serial_number_placeholder')"
            />
        </x-filament::input.wrapper>
    </x-filament::section>

    <x-filament-actions::modals />
</x-filament-widgets::widget>