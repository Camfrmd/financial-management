<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Keuangan Banjar - Masuk</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Tridatu Colors */
            --tri-black: #0a0a0a;
            --tri-white: #f5f5f5;
            --tri-red: #c62828;
            --tri-red-hover: #b71c1c;
            --surface-color: #141414;
            --border-color: #2a2a2a;
            --text-muted: #a0a0a0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: var(--tri-black);
            color: var(--tri-white);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 2rem;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            background-color: var(--tri-red);
            border-radius: 12px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--tri-white);
            letter-spacing: 1px;
            box-shadow: 0 4px 20px rgba(198, 40, 40, 0.2);
        }

        .brand h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .brand p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .login-card {
            background-color: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
        }

        /* Top Red Accent */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background-color: var(--tri-red);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--tri-white);
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            background-color: var(--tri-black);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--tri-white);
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--tri-red);
            box-shadow: 0 0 0 3px rgba(198, 40, 40, 0.15);
        }

        .form-control::placeholder {
            color: #555;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            cursor: pointer;
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .checkbox-container input {
            display: none;
        }

        .checkmark {
            width: 18px;
            height: 18px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            margin-right: 0.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.2s ease;
        }

        .checkbox-container input:checked ~ .checkmark {
            background-color: var(--tri-red);
            border-color: var(--tri-red);
        }

        .checkbox-container input:checked ~ .checkmark::after {
            content: '\2713';
            color: var(--tri-white);
            font-size: 12px;
            font-weight: bold;
        }

        .forgot-password {
            color: var(--tri-white);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s ease;
        }

        .forgot-password:hover {
            color: var(--tri-red);
        }

        .btn-primary {
            width: 100%;
            padding: 1rem;
            background-color: var(--tri-red);
            color: var(--tri-white);
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
        }

        .btn-primary:hover {
            background-color: var(--tri-red-hover);
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .alert-error {
            background-color: rgba(198, 40, 40, 0.1);
            border: 1px solid rgba(198, 40, 40, 0.3);
            color: #ff8a80;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
        }

        .alert-error ul {
            margin-left: 1.5rem;
            list-style-type: disc;
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="brand">
            <div class="brand-logo">SKB</div>
            <h1>Sistem Keuangan Banjar</h1>
            <p>Platform Pengelolaan Dana Komunitas</p>
        </div>

        <div class="login-card">
            @if ($errors->any())
                <div class="alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="nama@email.com" required autofocus autocomplete="username">
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input id="password" type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                </div>

                <div class="form-options">
                    <label class="checkbox-container">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        Ingat Saya
                    </label>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-password">Lupa Sandi?</a>
                    @endif
                </div>

                <button type="submit" class="btn-primary">
                    Masuk ke Dasbor
                </button>
            </form>
        </div>
    </div>

</body>
</html>
