<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatorio de Produtos</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
        }
        h1 {
            margin: 0 0 4px 0;
            font-size: 20px;
            color: #1a3d1f;
        }
        .meta {
            margin-bottom: 14px;
            color: #4b5563;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead th {
            background: #e8f5e9;
            color: #1a3d1f;
            text-align: left;
            font-size: 11px;
            padding: 8px;
            border: 1px solid #cfe4d1;
        }
        tbody td {
            border: 1px solid #e5e7eb;
            padding: 7px 8px;
            vertical-align: top;
        }
        .num {
            text-align: right;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <h1>{{ $reportTitle }}</h1>
    <div class="meta">
        Gerado em {{ $generatedAt->format('d/m/Y H:i') }} | Total de itens: {{ $rows->count() }}
        @if(!empty($appliedFilters))
            <br>
            Filtros:
            {{ collect($appliedFilters)->map(fn ($value, $label) => $label . ': ' . $value)->implode(' | ') }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                @foreach ($columns as $column)
                    <th @if(in_array($column['type'], ['int', 'money'], true)) class="num" @endif>
                        {{ $column['label'] }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    @foreach ($columns as $column)
                        @php
                            $value = data_get($row, $column['key']);
                        @endphp
                        <td @if(in_array($column['type'], ['int', 'money'], true)) class="num" @endif>
                            @if ($column['type'] === 'money')
                                R$ {{ number_format((float) ($value ?? 0), 2, ',', '.') }}
                            @elseif ($column['type'] === 'int')
                                {{ number_format((int) ($value ?? 0), 0, ',', '.') }}
                            @else
                                {{ $value ?? '-' }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}">Nenhum dado encontrado para os filtros selecionados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
