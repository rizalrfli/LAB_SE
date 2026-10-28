<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', $request->query('q', '')));
        $query = User::where('role', 'admin');
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'ILIKE', "%{$search}%")
                  ->orWhere('role', 'ILIKE', "%{$search}%");
            });
        }
        $users = $query->orderByDesc('id')->get()->toArray();
        $totalAdmin = User::where('role', 'admin')->count();

        return view('admin.user.index', [
            'current_page' => 'user',
            'page_title' => 'Kelola User - Admin Lab SE',
            'search' => $search,
            'users' => $users,
            'totalAdmin' => $totalAdmin,
        ]);
    }

    public function create()
    {
        return view('admin.user.tambah', [
            'current_page' => 'user',
            'page_title' => 'Tambah User - Admin Lab SE',
        ]);
    }

    public function store(Request $request)
    {
        $username = trim((string) $request->input('username', ''));
        $password = (string) $request->input('password', '');
        $passwordConfirm = (string) $request->input('password_confirm', '');

        if ($username === '' || mb_strlen($username) < 3) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Username minimal 3 karakter!')
                ->with('tipe_pesan', 'error');
        }
        if (mb_strlen($password) < 5) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Password minimal 5 karakter!')
                ->with('tipe_pesan', 'error');
        }
        if ($password !== $passwordConfirm) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Konfirmasi password tidak cocok!')
                ->with('tipe_pesan', 'error');
        }
        if (User::where('username', $username)->exists()) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Username sudah digunakan!')
                ->with('tipe_pesan', 'warning');
        }

        User::create([
            'username' => $username,
            'password' => md5($password),
            'role' => 'admin',
        ]);

        return redirect()->route('admin.user.index')
            ->with('pesan', 'User admin berhasil ditambahkan!')
            ->with('tipe_pesan', 'success');
    }

    public function edit(Request $request, $id = null)
    {
        $userId = (int) ($id ?? $request->query('id', 0));
        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('admin.user.index')
                ->with('pesan', 'Data user tidak ditemukan!')
                ->with('tipe_pesan', 'error');
        }

        return view('admin.user.edit', [
            'current_page' => 'user',
            'page_title' => 'Edit User - Admin Lab SE',
            'user' => $user->toArray(),
        ]);
    }

    public function update(Request $request, $id = null)
    {
        $userId = (int) ($id ?? $request->input('id', $request->query('id', 0)));
        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('admin.user.index')
                ->with('pesan', 'Data user tidak ditemukan!')
                ->with('tipe_pesan', 'error');
        }

        $username = trim((string) $request->input('username', ''));
        $password = (string) $request->input('password', '');
        $passwordConfirm = (string) $request->input('password_confirm', '');

        if ($username === '' || mb_strlen($username) < 3) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Username minimal 3 karakter!')
                ->with('tipe_pesan', 'error');
        }
        if (User::where('username', $username)->where('id', '!=', $userId)->exists()) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Username sudah digunakan oleh user lain!')
                ->with('tipe_pesan', 'warning');
        }

        $updateData = [
            'username' => $username,
            'role' => 'admin',
        ];

        if ($password !== '') {
            if (mb_strlen($password) < 5) {
                return redirect()->back()
                    ->withInput()
                    ->with('pesan', 'Password baru minimal 5 karakter!')
                    ->with('tipe_pesan', 'error');
            }
            if ($password !== $passwordConfirm) {
                return redirect()->back()
                    ->withInput()
                    ->with('pesan', 'Konfirmasi password tidak cocok!')
                    ->with('tipe_pesan', 'error');
            }
            $updateData['password'] = md5($password);
        }

        $user->update($updateData);

        return redirect()->route('admin.user.index')
            ->with('pesan', 'Data user berhasil diperbarui!')
            ->with('tipe_pesan', 'success');
    }

    public function destroy(Request $request, $id = null)
    {
        $userId = (int) ($id ?? $request->query('id', $request->input('id', 0)));
        $currentUserId = (int) (Auth::id() ?? session('user_id', 0));

        if ($userId === $currentUserId) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus akun yang sedang aktif!'], 400);
            }
            return redirect()->route('admin.user.index')
                ->with('pesan', 'Tidak dapat menghapus akun yang sedang aktif!')
                ->with('tipe_pesan', 'error');
        }

        $user = User::find($userId);
        if (!$user) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'User tidak ditemukan!'], 404);
            }
            return redirect()->route('admin.user.index')
                ->with('pesan', 'User tidak ditemukan!')
                ->with('tipe_pesan', 'error');
        }

        $username = $user->username;
        $user->delete();

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "User \"{$username}\" berhasil dihapus!",
                'id' => $userId,
                'nama' => $username,
            ]);
        }

        return redirect()->route('admin.user.index')
            ->with('pesan', "User \"{$username}\" berhasil dihapus!")
            ->with('tipe_pesan', 'success');
    }
}
