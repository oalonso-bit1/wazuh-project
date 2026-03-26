<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wazuh Agent | Console</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            color: white; margin: 0; overflow-x: hidden;
        }
        body::before {
            content: ""; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background-image: linear-gradient(rgba(0, 160, 210, 0.05) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(0, 160, 210, 0.05) 1px, transparent 1px);
            background-size: 40px 40px; z-index: -1;
        }
        .header-bar {
            border-bottom: 2px solid var(--wazuh-blue);
            background: rgba(22, 27, 34, 0.9);
            backdrop-filter: blur(10px);
        }
        .stat-card {
            background: var(--card-bg);
            border: 1px solid #30363d;
            border-left: 4px solid var(--wazuh-blue);
            border-radius: 8px;
            transition: 0.3s;
        }
        .stat-card:hover {
            border-color: var(--neon-green);
            box-shadow: 0 0 20px rgba(0, 255, 204, 0.15);
        }
        .api-key-box {
            background: rgba(0, 160, 210, 0.1);
            border: 1px dashed var(--wazuh-blue);
            cursor: pointer; border-radius: 6px;
        }
        .neon-text { color: var(--neon-green); text-shadow: 0 0 10px var(--neon-green); }
        .glow-blue { color: var(--wazuh-blue); text-shadow: 0 0 10px var(--wazuh-blue); }
        .pulse { animation: pulse-animation 2s infinite; }
        @keyframes pulse-animation { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
        .console-box {
            background: #000; border: 1px solid #333; color: #00ff00;
            font-size: 0.85rem; padding: 15px; border-radius: 5px;
        }
    </style>
</head>
<body>

    <nav class="header-bar p-3 d-flex justify-content-between align-items-center">
        <div>
            <span class="fw-bold glow-blue">WAZUH</span>
            <span class="text-white-50 ms-2">| Agente {{ $agenteNum }}</span>
        </div>
        <div class="d-flex align-items-center">
            <span class="small me-3 text-muted">ID: <span class="neon-text">{{ session('username') }}</span></span>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm border-0">[ DISCONNECT ]</button>
            </form>
        </div>
    </nav>

    <div class="container-fluid mt-4 px-4">
        <div class="row g-4">
            
            <div class="col-md-4">
                <div class="stat-card p-4 mb-4">
                    <h6 class="text-uppercase mb-4 glow-blue">Network Interface</h6>
                    <div class="mb-3">
                        <small class="text-muted d-block">IP ADDRESS (SISTEMA)</small>
                        <span class="neon-text h5 fw-bold">{{ $ip }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">OPERATING SYSTEM</small>
                        <span class="text-white small">{{ $os }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">SYSTEM UPTIME</small>
                        <span class="text-info small">{{ $uptime }}</span>
                    </div>
                    <div class="mt-4 text-end text-muted small">
                        PHP v{{ $php_version }}
                    </div>
                </div>

                <div class="stat-card p-4">
                    <h6 class="text-uppercase mb-3 glow-blue">Security Token</h6>
                    <div class="api-key-box p-3 text-center" onclick="copyToken()">
                        <code id="token" class="neon-text d-block" style="word-break: break-all;">{{ $user->api_key }}</code>
                        <small class="text-muted" style="font-size: 0.6rem;">CLICK PARA COPIAR</small>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="stat-card p-4 h-100">
                    <h5 class="mb-4"><span class="pulse neon-text">●</span> MONITORIZACIÓN EN TIEMPO REAL</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-dark table-hover border-secondary">
                            <thead class="text-muted small">
                                <tr>
                                    <th>SERVICIO</th>
                                    <th>ESTADO</th>
                                    <th>ÚLTIMA RESPUESTA</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                <tr>
                                    <td>Wazuh Module</td>
                                    <td><span class="badge bg-success">Running</span></td>
                                    <td>Sincronizado con 192.168.52.100</td>
                                </tr>
                                <tr>
                                    <td>Laravel Engine</td>
                                    <td><span class="badge bg-success">Online</span></td>
                                    <td>PHP {{ $php_version }}</td>
                                </tr>
                                <tr>
                                    <td>Firewall Filter</td>
                                    <td><span class="badge bg-info">Active</span></td>
                                    <td>Reglas de OPNSense aplicadas</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="console-box mt-4">
                        <div class="d-flex justify-content-between border-bottom border-secondary mb-2 pb-1">
                            <span style="font-size: 0.7rem; color: #666;">SYS_LOG_OUTPUT</span>
                        </div>
                        <code>
                            > [READY] Agente {{ $agenteNum }} conectado al Manager.<br>
                            > [{{ now()->format('Y-m-d H:i') }}] Broadcast IP: {{ $ip }} verificado.<br>
                            > Sincronizando políticas de seguridad...<br>
                            <span class="pulse">> _</span>
                        </code>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function copyToken() {
            const el = document.getElementById('token');
            navigator.clipboard.writeText(el.innerText);
            alert("Token de seguridad copiado.");
        }
    </script>
</body>
</html>