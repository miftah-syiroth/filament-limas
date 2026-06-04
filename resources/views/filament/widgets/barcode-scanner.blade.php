<x-filament-widgets::widget
    class="fi-wi-barcode-scanner"
    x-load
    x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('barcode-scanner') }}"
    x-data="barcodeScanner({ serialNumberLength: {{ \App\Filament\Widgets\BarcodeScanner::SERIAL_NUMBER_LENGTH }} })"
>
    <x-filament::section>
        <x-filament::input.wrapper :inline-prefix="true">
            <x-slot name="prefix">
                <x-filament::icon-button
                    :tooltip="__('barcode_scanner.scan_barcode')"
                    type="button"
                    size="lg"
                    icon="heroicon-o-camera"
                    x-on:click="startCamera()"
                />
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

    <div
        x-cloak
        x-show="scannerOpen"
        x-on:keydown.escape.window="stopCamera()"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/70 p-4"
    >
        <div
            class="mx-auto w-full max-w-sm overflow-hidden rounded-xl bg-white shadow-xl ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
            x-on:click.outside="stopCamera()"
        >
            <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3 dark:border-white/10">
                <h3 class="text-base font-semibold text-gray-950 dark:text-white">
                    {{ __('barcode_scanner.scan_barcode') }}
                </h3>

                <x-filament::icon-button
                    :tooltip="__('barcode_scanner.close_scanner')"
                    type="button"
                    icon="heroicon-o-x-mark"
                    x-on:click="stopCamera()"
                />
            </div>

            <div class="space-y-4 p-4">
                <div
                    id="barcode-scanner-reader"
                    wire:ignore
                    class="mx-auto aspect-[4/3] w-full max-w-[260px] overflow-hidden rounded-lg bg-gray-950 dark:bg-gray-950 [&_video]:object-cover"
                ></div>

                <p
                    x-show="isStarting"
                    class="text-sm text-gray-600 dark:text-gray-300"
                >
                    {{ __('barcode_scanner.starting_camera') }}
                </p>

                <p
                    x-show="error"
                    x-text="error"
                    class="text-sm text-danger-600 dark:text-danger-400"
                ></p>
            </div>
        </div>
    </div>

    <x-filament-actions::modals />
</x-filament-widgets::widget>
