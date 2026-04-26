@extends('layouts.layout')

@section('title', 'Profil Pengguna - EcoLend')

@push('styles')
    <style>
        .profile-hero {
            background: linear-gradient(135deg, #fff7e6 0%, #fff 45%, #fff0dd 100%);
            border: 1px solid #ffe3bf;
            border-radius: 1.5rem;
            padding: 1.25rem;
        }

        .profile-photo-wrap {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid #fff;
            box-shadow: 0 12px 30px rgba(255, 140, 0, 0.2);
            background: #fff3db;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-photo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-photo-empty {
            font-size: 3rem;
            color: #ff8c00;
        }

        .profile-kpi {
            border-radius: 1rem;
            border: 1px dashed #ffd8a5;
            background-color: #fffaf2;
            padding: 0.85rem;
        }

        .profile-kpi .label {
            font-size: 0.8rem;
            color: #8a8f98;
            margin-bottom: 0.25rem;
        }

        .profile-kpi .value {
            font-weight: 700;
            color: #1f2937;
        }
    </style>
@endpush

@section('content')
    <div class="profile-hero mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="fw-bold mb-1">Profil Pengguna</h2>
                <p class="text-muted mb-0">Kelola informasi akun, foto profil, dan identitas personal Anda.</p>
            </div>
            <div class="d-flex gap-2">
                <span class="badge rounded-pill text-bg-light border px-3 py-2">Role: {{ $user->role ?? '-' }}</span>
                <span class="badge rounded-pill text-bg-warning px-3 py-2">Aktif sejak {{ $activeSince ?? '-' }}</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-body p-4 text-center">
                    <div class="profile-photo-wrap mb-3">
                        @if($user->profilePhoto?->file_path)
                            <img src="{{ asset($user->profilePhoto->file_path) }}" alt="Foto Profil">
                        @else
                            <i class="bi bi-person-fill profile-photo-empty"></i>
                        @endif
                    </div>
                    <h4 class="fw-bold mb-0">{{ $user->name ?? '-' }}</h4>
                    <p class="text-muted text-capitalize mb-4">{{ $user->role ?? '-' }}</p>

                    @if ($errors->any())
                        <div class="alert alert-danger text-start py-2 px-3">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('profile.photo.upload') }}" method="POST" enctype="multipart/form-data" class="text-start">
                        @csrf
                        <label class="form-label fw-semibold">Upload Foto Profil</label>
                        <input type="file" name="profile_photo" class="form-control mb-3"
                            accept="image/png,image/jpeg,image/jpg,image/webp" required>
                        <button type="submit" class="btn btn-warning w-100 fw-semibold">
                            <i class="bi bi-cloud-arrow-up me-1"></i> Perbarui Foto
                        </button>
                        <small class="text-muted d-block mt-2">Format: JPG, PNG, WEBP. Maksimal 2MB.</small>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Informasi Akun</h5>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Nama Lengkap</label>
                            <input type="text" class="form-control" value="{{ $user->name ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Peran</label>
                            <div class="border rounded-3 px-3 py-2 bg-light d-flex align-items-center justify-content-between">
                                <span class="fw-semibold text-dark text-capitalize">{{ $user->role ?? '-' }}</span>
                                <i class="bi bi-shield-check text-warning"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Username</label>
                            <input type="text" class="form-control" value="{{ $user->username ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-kpi">
                                <div class="label">NPM</div>
                                <div class="value">{{ $user->npm ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-kpi">
                                <div class="label">Program Studi</div>
                                <div class="value">{{ $user->program_studi ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="profile-kpi">
                                <div class="label">No. Telepon</div>
                                <div class="value">{{ $user->no_telp ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Ubah Password</h5>
                    <form action="{{ route('profile.password.update') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label text-muted">Password Saat Ini</label>
                                <input type="password" name="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    placeholder="Masukkan password saat ini" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted">Password Baru</label>
                                <input type="password" name="new_password"
                                    class="form-control @error('new_password') is-invalid @enderror"
                                    placeholder="Minimal 8 karakter" required>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted">Konfirmasi Password Baru</label>
                                <input type="password" name="new_password_confirmation" class="form-control"
                                    placeholder="Ulangi password baru" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-warning fw-semibold px-4">
                                <i class="bi bi-key-fill me-1"></i> Simpan Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
