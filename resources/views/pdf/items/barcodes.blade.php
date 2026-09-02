<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            margin: 0;
            font-family: DejaVu Sans, sans-serif;
            color: #111827;
        }

        .sheet {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 4mm;
        }

        .label {
            border: 1px solid #d1d5db;
            border-radius: 4px;
            padding: 4mm 2.5mm 3mm;
            text-align: center;
            break-inside: avoid;
            min-height: 38mm;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .barcode {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .barcode img {
            width: 100%;
            height: auto;
            max-height: 18mm;
            object-fit: contain;
        }

        .serial {
            margin-top: 2mm;
            font-size: 8pt;
            font-weight: 700;
            letter-spacing: 0.06em;
        }

        .meta {
            margin-top: 1mm;
            font-size: 6pt;
            line-height: 1.2;
            color: #4b5563;
            min-height: 7mm;
        }
    </style>
</head>
<body>
    <div class="sheet">
        @foreach ($labels as $label)
            @php
                $item = data_get($label, 'item');
            @endphp

            <div class="label">
                <div class="barcode">
                    <img
                        src="data:image/png;base64,{{ data_get($label, 'barcode') }}"
                        alt="{{ $item->serial_number }}"
                    >
                </div>

                <div class="serial">{{ $item->serial_number }}</div>

                <div class="meta">
                    {{ $item->name ?: $item->model?->name }}
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
