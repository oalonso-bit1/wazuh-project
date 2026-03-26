<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wazuh Test - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/dist/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('static/style.css') }}">
</head>
<body>
    <div class="main-wrapper">
        <div class="login-card">
            <div class="brand-logo">
                <span class="neon-text1">WAZUH</span> <span class="neon-text2">TEST</span>
            </div>
            <form action="/login" method="POST" class="row g-3">
                @csrf
                <div class="col-12">
                    <label for="username" class="form-label">Usuario</label>
                    <input type="text" class="form-control" name="username" placeholder="User" required autofocus>
                </div>
                <div class="col-12">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="password" placeholder="••••••••" required>
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-wazuh w-100">Acceder al Panel</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>