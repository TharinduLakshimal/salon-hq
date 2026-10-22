<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name', 'Salon HQ') }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f3f4f6;
            color: #1f2937;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }
        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .login-header {
            text-align: center;
            margin-bottom: 1.75rem;
        }
        .login-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.35rem;
        }
        .login-header p {
            font-size: 0.875rem;
            color: #6b7280;
        }
        .error-box {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.4rem;
        }
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 0.65rem 0.85rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-group input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }
        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }
        .remember-label {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            color: #4b5563;
            cursor: pointer;
        }
        .btn-submit {
            width: 100%;
            background-color: #4f46e5;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.75rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.15s;
        }
        .btn-submit:hover {
            background-color: #4338ca;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <h1>Salon HQ</h1>
            <p>Sign in to your account</p>
        </div>

        @if ($errors->any())
            <div class="error-box">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="you@example.com"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    required
                >
            </div>

            <div class="remember-row">
                <label class="remember-label">
                    <input type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="btn-submit">Sign In</button>
        </form>
    </div>
</body>
</html>

