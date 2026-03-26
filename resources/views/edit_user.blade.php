<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wazuh Admin | Edit Agent</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --wazuh-blue: #00a0d2;
            --wazuh-dark: #0d1117;
            --neon-green: #00ffcc;
            --card-bg: #161b22;
        }

        body { 
            font-family: 'JetBrains Mono', monospace; 
            background-color: var(--wazuh-dark); 
            color: white;
            margin: 0;
        }

        /* Fondo de rejilla radar */
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background-image: linear-gradient(rgba(0, 160, 210, 0.05) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(0, 160, 210, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: -1;
        }

        /* Tarjeta centrada para edición */
        .edit-card {
            background: var(--card-bg);
            border: 1px solid #30363d;
            border-top: 4px solid var(--neon-green); /* Acento verde para indicar "Edición" */
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.6);
            width: 100%;
            max-width: 500px;
            position: relative;
        }

        .glow-title { 
            color: var(--neon-green); 
            text-shadow: 0 0 10px var(--neon-green); 
            letter-spacing: 2px;
            text-align: center;
            margin-bottom: 25px;
            font-size: 1.2rem;
        }

        /* Estilo para los Inputs unificado */
        .form-control, .form-select {
            background-color: #0d1117 !important;
            border: 1px solid #30363d !important;
            color: white !important;
            font-size: 0.9rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--neon-green) !important;
            box-shadow: 0 0 8px rgba(0, 255, 204, 0.3);
        }

        /* Botón de Guardar (Estilo Neón) */
        .btn-save {
            background: transparent;
            border: 1px solid var(--neon-green);
            color: var(--neon-green);
            font-weight: bold;
            letter-spacing: 1px;
            transition: 0.3s;
        }
        .btn-save:hover {
            background: var(--neon-green);
            color: black;
            box-shadow: 0 0 15px rgba(0, 255, 204, 0.4);
        }

        /* Botón de Cancelar */
        .btn-cancel {
            background: transparent;
            border: 1px solid #555;
            color: #aaa;
            transition: 0.3s;
            letter-spacing: 1px;
        }
        .btn-cancel:hover {
            background: #333;
            color: white;
            border-color: #888;
        }

        /* Decoración de esquina superior */
        .corner-decoration {
            position: absolute;
            top: 10px;
            right: 15px;
            color: #555;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    <div class="min-vh-100 d-flex align-items-center justify-content-center p-3">
        <div class="edit-card">
            <div class="corner-decoration">[ EDIT_MODE ]</div>
            
            <div class="glow-title fw-bold text-uppercase">
                <i class="bi bi-pencil-square me-2"></i>MODIFICAR AGENTE
            </div>
            
            <form action="/admin/update/{{ $u->id }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-info small fw-bold">IDENTIFICADOR DE USUARIO</label>
                    <input type="text" name="user" class="form-control" value="{{ $u->username }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-info small fw-bold">NUEVA CONTRASEÑA (Opcional)</label>
                    <input type="password" name="pass" class="form-control" placeholder="Dejar en blanco para mantener la actual" value="{{ $u->password }}">
                </div>

                <div class="mb-3">
                    <label class="form-label text-info small fw-bold">WAZUH API ACCESS KEY</label>
                    <input type="text" name="key" class="form-control" value="{{ $u->api_key }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label text-info small fw-bold">NIVEL DE ACCESO</label>
                    <select name="role" class="form-select">
                        <option value="agente" {{ $u->role == 'agente' ? 'selected' : '' }}>ESTÁNDAR (Agente)</option>
                        <option value="admin" {{ $u->role == 'admin' ? 'selected' : '' }}>ROOT (Admin)</option>
                    </select>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <a href="/admin" class="btn btn-cancel w-50">CANCELAR</a>
                    <button type="submit" class="btn btn-save w-50">GUARDAR</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>