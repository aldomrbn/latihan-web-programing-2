<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = User::orderBy('updated_at', 'desc')->get();
        
        return view('backend.v_user.index', [
            'judul' => 'Data User',
            'index' => $user
        ]);
    }

    public function create()
    {
        $judul = 'Tambah User';
        
        return view('backend.v_user.create', compact('judul'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|max:255',
            'email' => 'required|max:255|email|unique:user',
            'role' => 'required',
            'hp' => 'required|min:10|max:13',
            'password' => 'required|min:4|confirmed',
            'foto' => 'image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ], [
            'foto.image' => 'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.',
            'foto.max' => 'Ukuran file gambar Maksimal adalah 1024 KB.'
        ]);

        $validatedData['status'] = 0;

        // Menggunakan ImageHelper untuk upload dan resize foto
        if ($request->file('foto')) {
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-user/';

            // Simpan gambar dengan ukuran lebar 385px dan tinggi 400px
            ImageHelper::uploadAndResize($file, $directory, $originalFileName, 385, 400);

            // Simpan nama file ke database
            $validatedData['foto'] = $originalFileName;
        }

        // Validasi password kombinasi (huruf besar, huruf kecil, angka, dan simbol)
        $password = $request->input('password');
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/';

        if (preg_match($pattern, $password)) {
            $validatedData['password'] = Hash::make($validatedData['password']);
            User::create($validatedData);

            return redirect()->route('backend.user.index')->with('success', 'Data berhasil tersimpan');
        } else {
            return redirect()->back()->withErrors([
                'password' => 'Password harus terdiri dari kombinasi huruf besar, huruf kecil, angka, dan simbol karakter.'
            ]);
        }
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        
        return view('backend.v_user.edit', [
            'judul' => 'Ubah User',
            'edit' => $user
        ]);
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        
        $rules = [
            'nama' => 'required|max:255',
            'role' => 'required',
            'status' => 'required',
            'hp' => 'required|min:10|max:13',
            'foto' => 'image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ];

        $messages = [
            'foto.image' => 'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.',
            'foto.max' => 'Ukuran file gambar Maksimal adalah 1024 KB.'
        ];

        // Jika email diubah, validasi keunikan email
        if ($request->email != $user->email) {
            $rules['email'] = 'required|max:255|email|unique:user';
        }

        $validatedData = $request->validate($rules, $messages);

        // Menggunakan ImageHelper untuk upload foto baru
        if ($request->file('foto')) {
            // Hapus gambar lama jika ada
            if ($user->foto) {
                $oldImagePath = public_path('storage/img-user/') . $user->foto;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-user/';

            // Simpan gambar dengan ukuran lebar 385px dan tinggi 400px
            ImageHelper::uploadAndResize($file, $directory, $originalFileName, 385, 400);

            // Simpan nama file baru ke database
            $validatedData['foto'] = $originalFileName;
        }

        $user->update($validatedData);

        return redirect()->route('backend.user.index')->with('success', 'Data berhasil diperbaharui');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Hapus file foto jika ada di dalam direktori penyimpanan
        if ($user->foto) {
            $oldImagePath = public_path('storage/img-user/') . $user->foto;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        // Hapus data user dari database
        $user->delete();

        return redirect()->route('backend.user.index')->with('success', 'Data berhasil dihapus');
    }
}