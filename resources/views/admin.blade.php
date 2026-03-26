<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wazuh Admin | Control Panel</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --wazuh-blue: #00a0d2;
            --wazuh-dark: #0d1117;
            --neon-green: #00ffcc;
            --danger-red: #ff3e3e;
            --wazuh-red-neon: #ff004c; /* Rojo neón brillante */
            --card-bg: #161b22;
        }

        body { 
            font-family: 'JetBrains Mono', monospace; 
            background-color: var(--wazuh-dark); 
            color: white;
            margin: 0;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background-image: linear-gradient(rgba(0, 160, 210, 0.05) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(0, 160, 210, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: -1;
        }

        .header-bar {
            border-bottom: 2px solid var(--wazuh-blue);
            background: rgba(22, 27, 34, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
        }

        .admin-card {
            background: var(--card-bg);
            border: 1px solid #30363d;
            border-top: 4px solid var(--wazuh-blue);
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .controls-wrapper {
            background: rgba(0, 0, 0, 0.2);
            padding: 15px;
            border-radius: 0 0 8px 8px;
            border: 1px solid #333;
            border-top: none;
        }

        .neon-text { color: var(--neon-green); text-shadow: 0 0 10px var(--neon-green); }
        .glow-blue { color: var(--wazuh-blue); text-shadow: 0 0 10px var(--wazuh-blue); }

        .form-control, .form-select {
            background-color: #0d1117 !important;
            border: 1px solid #30363d !important;
            color: white !important;
            font-size: 0.9rem;
        }
        
        .form-control::placeholder { color: #555 !important; }

        .table { border-color: #30363d; }
        .table thead th { 
            background: rgba(0, 160, 210, 0.1); 
            color: var(--wazuh-blue); 
            text-transform: uppercase;
            font-size: 0.75rem;
        }

        .btn-wazuh {
            background: transparent;
            border: 1px solid var(--wazuh-blue);
            color: var(--wazuh-blue);
            transition: 0.3s;
            font-weight: bold;
        }
        .btn-wazuh:hover {
            background: var(--wazuh-blue);
            color: white;
            box-shadow: 0 0 15px rgba(0, 160, 210, 0.4);
        }

        .pagination { margin: 0; }
        .page-link { background: #1a1a1a; border-color: #00a0d2; color: #00a0d2; padding: 5px 12px;}
        .page-item.active .page-link { background: #00a0d2; border-color: #00a0d2; color: white; }
        .page-item.disabled .page-link { background: transparent !important; border-color: transparent !important; color: var(--wazuh-blue) !important; opacity: 0.6;}

        /* Estilos de botones de acción simétricos */
        .action-btn { 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            width: 32px; height: 32px; 
            border-radius: 4px; 
            transition: 0.2s; 
            border: 1px solid transparent; 
            font-size: 0.9rem;
        }

        /* Botón EDIT (Verde neón) */
        .btn-edit { 
            color: var(--neon-green); 
            border: 1px solid var(--neon-green); 
        }
        .btn-edit:hover {
            background: rgba(0, 255, 204, 0.1); 
            box-shadow: 0 0 8px var(--neon-green);
        }

        /* Botón DELETE (Rojo neón unificado) */
        .btn-delete { 
            color: var(--wazuh-red-neon); 
            border: 1px solid var(--wazuh-red-neon); 
            background: transparent;
        }
        .btn-delete:hover { 
            background: rgba(255, 0, 76, 0.15) !important; 
            color: #ff3c7d !important; 
            box-shadow: 0 0 8px var(--wazuh-red-neon);
        }
    </style>
</head>
<body>

    <nav class="header-bar d-flex justify-content-between align-items-center mb-4">
    <div>
        <span class="fw-bold glow-blue">WAZUH</span>
        <span class="text-white-50 ms-2 small text-uppercase">| Administracion de Usuarios</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="small me-4 text-muted">ADMIN: <span class="neon-text">{{ strtoupper(session('username')) }}</span></span>
        
        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm border-0 fw-bold">
                [ CERRAR SESIÓN ]
            </button>
        </form>
    </div>
</nav>

    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="admin-card">
                    <h6 class="glow-blue mb-4 text-uppercase"><i class="bi bi-person-plus me-2"></i>Registrar Nuevo Agente</h6>
                    <form action="/admin/create" method="POST" class="row g-3">
                        @csrf
                        <div class="col-md-3">
                            <label class="text-info small fw-bold mb-1">USUARIO</label>
                            <input type="text" name="user" class="form-control" placeholder="Ej: agente_01" required>
                        </div>
                        <div class="col-md-3">
                            <label class="text-info small fw-bold mb-1">PASSWORD</label>
                            <input type="password" name="pass" class="form-control" placeholder="••••••••" required>
                        </div>
                        <div class="col-md-3">
                            <label class="text-info small fw-bold mb-1">WAZUH_API_KEY</label>
                            <input type="text" name="key" class="form-control" placeholder="Base64 Key" required>
                        </div>
                        <div class="col-md-2">
                            <label class="text-info small fw-bold mb-1">ROLE</label>
                            <select name="role" class="form-select">
                                <option value="agente">AGENTE</option>
                                <option value="admin">ADMINISTRADOR</option>
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-wazuh w-100">ADD</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-12">
                <div class="admin-card p-0" style="overflow: hidden;">
                    <div class="p-4 border-bottom border-secondary d-flex justify-content-between align-items-center">
                        <h6 class="glow-blue text-uppercase m-0"><i class="bi bi-shield-lock me-2"></i>Base de Datos de Usuarios</h6>
                    </div>

                    <div class="table-responsive px-4 pt-2">
                        <table class="table table-dark table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>USUARIO</th>
                                    <th>API ACCESS KEY</th>
                                    <th>PRIVILEGIOS</th>
                                    <th class="text-end">OPERACIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuarios as $u)
                                <tr>
                                    <td class="fw-bold">{{ $u->username }}</td>
                                    <td><code class="text-info">{{ $u->api_key }}</code></td>
                                    <td>
                                        <span class="badge rounded-pill {{ $u->role == 'admin' ? 'bg-danger' : 'bg-info text-dark' }}" style="font-size: 0.6rem;">
                                            {{ strtoupper($u->role) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="/admin/edit/{{ $u->id }}" class="action-btn btn-edit" title="Editar credenciales">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="/admin/delete/{{ $u->id }}" method="POST" class="m-0" onsubmit="return confirm('¿Destruir credenciales permanentemente?')">
                                                @csrf @method('DELETE')
                                                <button class="action-btn btn-delete" title="Destruir credenciales de agente">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="controls-wrapper d-flex justify-content-between align-items-center">
                        <form action="/admin" method="GET" class="d-flex align-items-center gap-2">
                            <label class="text-info small fw-bold">MOSTRAR:</label>
                            <select name="limit" onchange="this.form.submit()" class="form-select form-select-sm" style="width: 70px; background: #1a1a1a; color: white; border: 1px solid #00a0d2;">
                                <option value="5" {{ request('limit') == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('limit') == 20 ? 'selected' : '' }}>20</option>
                            </select>
                        </form>

                        <div>
                            {{ $usuarios->onEachSide(1)->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>