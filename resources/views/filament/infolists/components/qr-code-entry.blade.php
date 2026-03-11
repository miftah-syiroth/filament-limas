<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    <div {{ $getExtraAttributeBag() }}>
        @if ($getState())
            <img src="{{ $getQrCodeImage() }}" alt="{{ $getState() }}" width="100" height="100" />
        @endif
    </div>
</x-dynamic-component>
