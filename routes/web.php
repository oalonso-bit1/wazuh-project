<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Vista de Login
Route::get('/', function () { return view('login'); });

// Sustituye tus rutas de logout por esta:
Route::any('/logout', function (Request $request) {
    session()->forget(['role', 'username']);
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Lógica de Login Dinámica
Route::post('/login', function (Request $request) {
    $user = DB::table('usuarios')
                ->where('username', $request->username)
                ->where('password', $request->password)
                ->first();

    if ($user) {
        session(['role' => $user->role, 'username' => $user->username]);

        if ($user->role === 'admin') {
            return redirect('/admin');
        } else {
            // --- DETECCIÓN DE IP REAL ---
            $ip_cmd = "hostname -I | awk '{print $1}'"; 
            $real_ip = trim(shell_exec($ip_cmd)) ?: $request->ip();

            // --- CÁLCULO DEL NÚMERO DE AGENTE ---
            // Contamos cuántos 'agentes' hay con ID menor o igual al actual
            $agenteNum = DB::table('usuarios')
                            ->where('role', 'agente')
                            ->where('id', '<=', $user->id)
                            ->count();

            $data = [
                'user' => $user,
                'ip'   => $real_ip,
                'os'   => PHP_OS_FAMILY . " (" . php_uname('m') . ")",
                'php_version' => PHP_VERSION,
                'uptime' => shell_exec("uptime -p") ?? 'System Up',
                'agenteNum' => $agenteNum // Pasamos el número calculado
            ];

            return view('welcome_agente', $data);
        }
    }
    return back()->with('error', 'Credenciales incorrectas');
});

// Panel de Admin
Route::get('/admin', function (Request $request) {
    if (session('role') !== 'admin') {
        return redirect('/')->with('error', 'No tienes permiso.');
    }

    // Capturamos el límite, por defecto 5
    $limit = $request->get('limit', 5);

    // Usamos paginate en lugar de get()
    $usuarios = DB::table('usuarios')->paginate($limit)->withQueryString();

    return view('admin', ['usuarios' => $usuarios]);
});

// 1. Vista de formulario de edición
Route::get('/admin/edit/{id}', function ($id) {
    if (session('role') !== 'admin') return redirect('/');
    
    $usuario = DB::table('usuarios')->where('id', $id)->first();
    return view('edit_user', ['u' => $usuario]);
});

// 2. Lógica para procesar la actualización
Route::post('/admin/update/{id}', function (Request $request, $id) {
    if (session('role') !== 'admin') return redirect('/');

    DB::table('usuarios')->where('id', $id)->update([
        'username' => $request->user,
        'password' => $request->pass, 
        'api_key'  => $request->key,
        'role'     => $request->role
    ]);
    
    return redirect('/admin');
});

// Crear Usuario
Route::post('/admin/create', function (Request $request) {
    DB::table('usuarios')->insert([
        'username' => $request->user,
        'password' => $request->pass,
        'api_key'  => $request->key,
        'role'     => $request->role ?? 'agente'
    ]);
    return back();
});

// Eliminar Usuario
Route::delete('/admin/delete/{id}', function ($id) {
    DB::table('usuarios')->where('id', $id)->delete();
    return back();
});

// Ruta para Cerrar Sesión
Route::get('/logout', function () {
    session()->forget(['role', 'username']);
    return redirect('/');
});