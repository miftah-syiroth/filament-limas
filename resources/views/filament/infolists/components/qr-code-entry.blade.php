<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    <div {{ $getExtraAttributeBag() }}>
        <img src="{{ $getState() }}" alt="{{ $getRecord()->serial_number }}" width="100" height="100" />
    </div>
</x-dynamic-component>
