<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\FileManager;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /* =======================
     * FORM
     * ======================= */
    public function profile()
    {
        $user = Auth::user()?->load('profilePhoto');

        if (!$user) {
            return redirect()->route('auth.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $activeSince = $user->created_at
            ? $user->created_at->translatedFormat('M Y')
            : '-';

        return view('profile', compact('user', 'activeSince'));
    }

    public function uploadProfilePhoto(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $validated = $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'profile_photo.required' => 'Foto profil harus dipilih.',
            'profile_photo.image' => 'File harus berupa gambar.',
            'profile_photo.mimes' => 'Format file harus JPG, JPEG, PNG, atau WEBP.',
            'profile_photo.max' => 'Ukuran file maksimal 2MB.',
        ]);

        try {
            $file = $validated['profile_photo'];
            $folder = 'profile';

            $basePath = storage_path("app/public/uploads/$folder");
            if (!File::exists($basePath)) {
                File::makeDirectory($basePath, 0777, true);
            }

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $cleanName = Str::slug($originalName);
            $fileName = time() . '_' . $cleanName . '.' . $extension;

            $file->storeAs("uploads/$folder", $fileName, 'public');

            $fileModel = FileManager::create([
                'file_name'   => $fileName,
                'file_path'   => "storage/uploads/$folder/$fileName",
                'mime_type'   => $file->getClientMimeType(),
                'size'        => $file->getSize(),
                'uploaded_by' => $user->id,
            ]);

            $user->profile_id = $fileModel->id;
            $user->save();

            return redirect()->route('profile')->with('success', 'Foto profil berhasil diperbarui.');
        } catch (\Throwable $e) {
            return redirect()->route('profile')->with('error', 'Gagal mengunggah foto profil. Silakan coba lagi.');
        }
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'new_password.different' => 'Password baru harus berbeda dari password saat ini.',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->route('profile')
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->withInput();
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return redirect()->route('profile')->with('success', 'Password berhasil diperbarui.');
    }

    public function getDataUser(Request $request)
    {
        $userId = $request->input('user_id', Auth::id());

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna belum terautentikasi.'
            ], 401);
        }

        $user = User::with('profilePhoto:id,file_path')
            ->select('id', 'name', 'username', 'email', 'role', 'created_at', 'last_active_at', 'profile_id')
            ->find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengguna tidak ditemukan.'
            ], 404);
        }

        $activeSince = $user->created_at ? $user->created_at->translatedFormat('M Y') : null;
        $lastActive = $user->last_active_at ? Carbon::parse($user->last_active_at)->diffForHumans() : null;

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'active_since' => $activeSince,
                'last_active_at' => $lastActive,
                'profile_photo_url' => $user->profilePhoto?->file_path ? asset($user->profilePhoto->file_path) : null,
            ]
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'npm' => ['nullable', 'string', 'max:50'],
            'program_studi' => ['nullable', 'string', 'max:100'],
            'no_telp' => ['nullable', 'string', 'max:20'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
        ]);

        $user->update($validated);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }
}