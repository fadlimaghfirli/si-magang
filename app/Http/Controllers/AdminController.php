<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Mengambil data Admin dengan fitur pencarian.
     */
    public function getDataAdmin(Request $request)
    {
        // Ambil query pencarian dari request
        $search = $request->input('search');

        // Mulai query Admin dengan relasi user
        $query = Admin::with('user');

        // Jika ada query pencarian, terapkan filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                // Cari di kolom 'nama' tabel 'admins'
                $q->where('nama', 'like', '%' . $search . '%')
                    // Atau cari di kolom 'email' tabel 'admins'
                    ->orWhere('email', 'like', '%' . $search . '%')
                    // Atau cari di kolom 'username' pada tabel 'users' yang terelasi
                    ->orWhereHas('user', function ($q_user) use ($search) {
                        $q_user->where('username', 'like', '%' . $search . '%');
                    });
            });
        }

        // Eksekusi query
        $admins = $query->get();

        // Kirim data admins dan search query ke view
        return view('admin.index', [
            'admins' => $admins,
            'search' => $search, // Kirimkan query pencarian untuk dipertahankan di form
        ]);
    }

    /**
     * Metode baru untuk menyimpan admin.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users', // Pastikan unik di tabel users
            'email' => 'required|string|email|max:255|unique:admins', // Pastikan unik di tabel admins
            'password' => 'required|string|min:8', // Tambahkan 'confirmed' jika ada field konfirmasi password
        ]);

        // 2. Buat User terlebih dahulu
        // Pastikan model User Anda punya 'username' dan 'password' di $fillable
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            // Jika email juga ada di tabel User, tambahkan di sini:
            // 'email' => $request->email,
        ]);

        // 3. Buat Admin dan hubungkan dengan User ID
        // Pastikan model Admin Anda punya 'nama', 'email', 'user_id' di $fillable
        Admin::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'user_id' => $user->id,
        ]);

        // 4. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.index')->with('success', 'Admin created successfully.');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return redirect()->route('admin.index')->with('success', 'Admin deleted successfully.');
    }

    /**
     * Metode baru untuk menghapus SEMUA Admin dan User terkait, serta mereset ID.
     */
    public function destroyAll()
    {
        // Dapatkan semua user_id dari tabel admins
        $userIds = Admin::pluck('user_id')->filter()->unique()->toArray();

        // Menggunakan TRUNCATE untuk menghapus semua data Admin dan mereset auto-increment.
        // TRUNCATE lebih cepat dan otomatis mereset ID, namun butuh penanganan foreign key.
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Admin::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Hapus semua User yang ID-nya ada di daftar user_id admins
        if (!empty($userIds)) {
            User::whereIn('id', $userIds)->delete();
        }

        // Opsional: Mereset auto-increment untuk tabel users juga (jika perlu)
        // DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');

        return redirect()->route('admin.index')->with('success', 'Semua Admin dan User terkait telah berhasil dihapus dan ID direset.');
    }
}
