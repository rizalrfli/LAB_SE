<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() || session('login')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $username = trim((string) $request->input('username', ''));
        $password = (string) $request->input('password', '');

        if ($username === '' || $password === '') {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Username dan password wajib diisi!')
                ->with('tipe_pesan', 'error');
        }

        try {
            $user = User::where('username', $username)->first();

            $authenticated = false;
            if ($user) {
                if ($user->password === md5($password)) {
                    $authenticated = true;
                } elseif (str_starts_with($user->password, '$2y$') && Hash::check($password, $user->password)) {
                    $authenticated = true;
                }
            }

            if ($authenticated && $user) {
                Auth::login($user);
                session([
                    'login' => true,
                    'user_id' => (int) $user->id,
                    'username' => $user->username,
                    'role' => $user->role ?? 'admin',
                ]);

                return redirect()->route('admin.dashboard');
            }
        } catch (Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Gagal terhubung ke database: ' . $e->getMessage())
                ->with('tipe_pesan', 'error');
        }

        return redirect()->back()
            ->withInput()
            ->with('pesan', 'Username atau password salah!')
            ->with('tipe_pesan', 'error');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
