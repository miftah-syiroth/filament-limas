<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    <div {{ $getExtraAttributeBag() }}>
        @if ($getState())
            {!! $getQrCodeImage() !!}
        @endif
    </div>
</x-dynamic-component>
