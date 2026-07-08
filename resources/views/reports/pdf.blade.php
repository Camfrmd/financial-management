<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>LPJ - {{ $fundName }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 12px;
        }
        .section-title {
            background-color: #f0f0f0;
            font-weight: bold;
            padding: 5px;
            margin-top: 15px;
            border: 1px solid #ccc;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
        }
        th {
            background-color: #f9f9f9;
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .text-green {
            color: #15803d; /* Green 700 */
        }
        .text-red {
            color: #b91c1c; /* Red 700 */
        }
        .indent {
            padding-left: 20px;
            font-style: italic;
            color: #555;
        }
        .signatures {
            margin-top: 50px;
            width: 100%;
        }
        .signatures td {
            border: none;
            width: 50%;
            text-align: center;
            padding-top: 50px;
        }
        .signatures .name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN PERTANGGUNGJAWABAN (LPJ) KEUANGAN BANJAR</h1>
        <p><strong>Periode:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</p>
        <p><strong>Cakupan Dana:</strong> {{ $fundName }}</p>
    </div>

    <!-- Starting Balance -->
    <table>
        <tr>
            <td class="font-bold">Saldo Awal (Per {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }})</td>
            <td class="text-right font-bold {{ $startingBalance >= 0 ? 'text-green' : 'text-red' }}" style="width: 150px;">
                Rp {{ number_format($startingBalance, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <!-- Incomes -->
    <div class="section-title">PEMASUKAN (INCOMES)</div>
    <table>
        <thead>
            <tr>
                <th>Kategori / Keterangan</th>
                <th style="width: 150px;" class="text-right">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @if(empty($groupedIncomes))
                <tr><td colspan="2" class="text-center">Tidak ada transaksi pemasukan.</td></tr>
            @else
                @foreach($groupedIncomes as $parent => $data)
                    <tr>
                        <td class="font-bold">{{ $parent }}</td>
                        <td class="text-right font-bold">Rp {{ number_format($data['total'], 0, ',', '.') }}</td>
                    </tr>
                    @foreach($data['details'] as $child => $amount)
                        <tr>
                            <td class="indent">- {{ $child }}</td>
                            <td class="text-right">Rp {{ number_format($amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td class="font-bold text-right">TOTAL PEMASUKAN</td>
                <td class="text-right font-bold text-green">Rp {{ number_format($totalIncomes, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Expenses -->
    <div class="section-title">PENGELUARAN (EXPENSES)</div>
    <table>
        <thead>
            <tr>
                <th>Kategori / Keterangan</th>
                <th style="width: 150px;" class="text-right">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @if(empty($groupedExpenses))
                <tr><td colspan="2" class="text-center">Tidak ada transaksi pengeluaran.</td></tr>
            @else
                @foreach($groupedExpenses as $parent => $data)
                    <tr>
                        <td class="font-bold">{{ $parent }}</td>
                        <td class="text-right font-bold">Rp {{ number_format($data['total'], 0, ',', '.') }}</td>
                    </tr>
                    @foreach($data['details'] as $child => $amount)
                        <tr>
                            <td class="indent">- {{ $child }}</td>
                            <td class="text-right">Rp {{ number_format($amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td class="font-bold text-right">TOTAL PENGELUARAN</td>
                <td class="text-right font-bold text-red">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Ending Balance -->
    <table style="margin-top: 20px;">
        <tr>
            <td class="font-bold" style="font-size: 14px; background-color: #e5e7eb;">Saldo Akhir (Per {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }})</td>
            <td class="text-right font-bold {{ $endingBalance >= 0 ? 'text-green' : 'text-red' }}" style="width: 150px; font-size: 14px; background-color: #e5e7eb;">
                Rp {{ number_format($endingBalance, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <!-- Signatures -->
    <table class="signatures">
        <tr>
            <td>
                <div>Mengetahui,</div>
                <div style="margin-bottom: 60px;">Kelian Adat</div>
                <div class="name">......................................</div>
            </td>
            <td>
                <div>Disusun Oleh,</div>
                <div style="margin-bottom: 60px;">Bendahara (Treasurer)</div>
                <div class="name">......................................</div>
            </td>
        </tr>
    </table>

</body>
</html>
