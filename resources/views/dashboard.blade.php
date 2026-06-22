<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Keuangan Banjar - Dashboard</title>
    <style>
        :root {
            --bg-color: #121212;
            --surface-color: #1e1e1e;
            --primary-red: #d32f2f;
            --text-white: #ffffff;
            --text-gray: #b0b0b0;
            --border-color: #333333;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-white);
        }

        .navbar {
            background-color: var(--surface-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--primary-red);
        }

        .navbar h1 {
            font-size: 1.25rem;
        }

        .logout-btn {
            background-color: transparent;
            color: var(--text-white);
            border: 1px solid var(--border-color);
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .card {
            background-color: var(--surface-color);
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .card h2 {
            font-size: 1rem;
            color: var(--text-gray);
            margin-bottom: 0.5rem;
        }

        .card .value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-red);
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <h1>Sistem Keuangan Banjar</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Keluar</button>
        </form>
    </nav>

    <div class="container">
        <div class="dashboard-grid">
            <div class="card">
                <h2>Total Saldo Kas (Contoh)</h2>
                <div class="value">Rp 15.000.000</div>
            </div>
            <div class="card">
                <h2>Pengeluaran Bulan Ini (Contoh)</h2>
                <div class="value">Rp 2.500.000</div>
            </div>
            <div class="card">
                <h2>Pemasukan Bulan Ini (Contoh)</h2>
                <div class="value">Rp 5.000.000</div>
            </div>
        </div>

        <div class="card">
            <h2>Transaksi Terakhir</h2>
            <p style="color: var(--text-gray); margin-top: 1rem;">(Tabel transaksi akan ditampilkan di sini)</p>
        </div>
    </div>

</body>
</html>
