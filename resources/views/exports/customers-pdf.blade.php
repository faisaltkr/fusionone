<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Customers</title>
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { font-size: 10px; color: #1f2937; margin: 0; }
        .header { margin-bottom: 12px; }
        .header h1 { font-size: 18px; margin: 0 0 2px 0; }
        .header .meta { font-size: 9px; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            background: #111827;
            color: #ffffff;
            text-align: left;
            padding: 6px 8px;
            font-size: 9px;
            text-transform: uppercase;
        }
        tbody td {
            padding: 5px 8px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        tbody tr:nth-child(even) { background: #f9fafb; }
        .empty { text-align: center; padding: 20px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Customers</h1>
        <div class="meta">
            Total: {{ count($rows) }} &middot; Generated: {{ $generatedAt }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                @foreach ($headings as $heading)
                    <th>{{ $heading }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    @foreach ($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td class="empty" colspan="{{ count($headings) }}">No customers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
