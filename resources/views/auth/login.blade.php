<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Pelaporan MBG</title>
    <style>
        body { font-family: sans-serif; background: #f3f4f6; display: flex; justify-content: center; padding-top: 80px; }
        .kartu { background: #fff; padding: 24px; border-radius: 8px; width: 320px; box-shadow: 0 1px 4px rgba(0,0,0,.15); }
        label { display: block; margin-top: 12px; font-size: 14px; }
        input { width: 100%; padding: 8px; margin-top: 4px; box-sizing: border-box; }
        button { margin-top: 16px; width: 100%; padding: 10px; background: #2563eb; color: #fff; border: 0; border-radius: 4px; cursor: pointer; }
        .error { color: #b91c1c; font-size: 13px; margin-top: 8px; }
    </style>
</head>
<body>
    <div class="kartu">
        <h2>Login</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label>Email
                <input type="email" name="email" value="{{ old('email') }}" required>
            </label>
            <label>Password
                <input type="password" name="password" required>
            </label>
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror
            <button type="submit">Masuk</button>
        </form>
    </div>
</body>
</html>