<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    <div {{ $getExtraAttributeBag() }}>
        <img src="{{ $getState() }}" alt="QR Code" />
    </div>
</x-dynamic-component>
