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

        .profile-photo-wrap button {
            border: 0;
            background: transparent;
            padding: 0;
            width: 100%;
            height: 100%;
        }

        .profile-photo-clickable {
            cursor: pointer;
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

        .photo-preview {
            border: 1px dashed #ffd8a5;
            border-radius: 0.75rem;
            padding: 0.75rem;
            background: #fffaf2;
        }

        .photo-preview img {
            width: 100%;
            max-height: 220px;
            object-fit: cover;
            border-radius: 0.5rem;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/cropperjs@1.6.2/dist/cropper.min.css">
@endpush

@section('content')
    <div class="profile-hero mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="fw-bold mb-1">Profil Pengguna</h2>
            </div>
            <div class="d-flex gap-2">
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
                            <button type="button" class="profile-photo-clickable" data-bs-toggle="modal" data-bs-target="#profilePhotoModal">
                                <img src="{{ asset($user->profilePhoto->file_path) }}" alt="Foto Profil" id="profilePhotoThumb">
                            </button>
                        @else
                            <i class="bi bi-person-fill profile-photo-empty"></i>
                        @endif
                    </div>
                    <h4 class="fw-bold mb-0">{{ $user->name ?? '-' }}</h4>
                    <p class="text-muted text-capitalize mb-4">
                        {{ ($user->role ?? null) === 'peminjam' ? 'Mahasiswa' : ($user->role ?? '-') }}
                    </p>

                    @if ($errors->any())
                        <div class="alert alert-danger text-start py-2 px-3">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('profile.photo.upload') }}" method="POST" enctype="multipart/form-data" class="text-start">
                        @csrf
                        <label class="form-label fw-semibold">Upload Foto Profil</label>
                        <input type="file" name="profile_photo" id="profilePhotoInput" class="form-control mb-3"
                            accept="image/png,image/jpeg,image/jpg,image/webp" required>
                        <div class="photo-preview mb-3 d-none" id="photoPreviewWrap">
                            <img id="photoPreview" alt="Preview Foto Profil">
                        </div>
                        <div class="d-flex gap-2 mb-3">
                            <button type="button" class="btn btn-outline-secondary w-100 d-none" id="openCropModal">
                                <i class="bi bi-crop me-1"></i> Atur Foto
                            </button>
                        </div>
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
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Nama Lengkap</label>
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}"
                                    required
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Status</label>
                                <div class="border rounded-3 px-3 py-2 bg-light d-flex align-items-center justify-content-between">
                                    <span class="fw-semibold text-dark text-capitalize">
                                        {{ ($user->role ?? null) === 'peminjam' ? 'Mahasiswa' : ($user->role ?? '-') }}
                                    </span>
                                    <i class="bi bi-shield-check text-warning"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Email</label>
                                <input type="email" class="form-control" value="{{ $user->email ?? '-' }}" readonly>
                                <small class="text-muted">Email tidak dapat diubah.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Username</label>
                                <input
                                    type="text"
                                    name="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    value="{{ old('username', $user->username) }}"
                                    required
                                >
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">NPM</label>
                                <input
                                    type="text"
                                    name="npm"
                                    class="form-control @error('npm') is-invalid @enderror"
                                    value="{{ old('npm', $user->npm) }}"
                                    readonly
                                >
                                <small class="text-muted">NPM tidak dapat diubah.</small>
                                @error('npm')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Program Studi</label>
                                <input
                                    type="text"
                                    name="program_studi"
                                    class="form-control @error('program_studi') is-invalid @enderror"
                                    value="{{ old('program_studi', $user->program_studi) }}"
                                    readonly
                                >
                                <small class="text-muted">Program studi tidak dapat diubah.</small>
                                @error('program_studi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-muted">No. Telepon</label>
                                <input
                                    type="text"
                                    name="no_telp"
                                    class="form-control @error('no_telp') is-invalid @enderror"
                                    value="{{ old('no_telp', $user->no_telp) }}"
                                >
                                @error('no_telp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-warning fw-semibold px-4">
                                <i class="bi bi-save2 me-1"></i> Simpan Profil
                            </button>
                        </div>
                    </form>
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

    <div class="modal fade" id="cropperModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Atur Foto Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="w-100" style="max-height: 420px; overflow: hidden;">
                        <img id="cropperImage" alt="Crop Foto" style="width: 100%;">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary" id="zoomIn">
                            <i class="bi bi-zoom-in me-1"></i> Zoom In
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="zoomOut">
                            <i class="bi bi-zoom-out me-1"></i> Zoom Out
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="rotateLeft">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Putar
                        </button>
                    </div>
                    <button type="button" class="btn btn-warning" id="applyCrop">
                        <i class="bi bi-check2-circle me-1"></i> Gunakan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="profilePhotoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Foto Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="profilePhotoPreview" class="img-fluid rounded-4" alt="Foto Profil" style="max-height: 70vh;">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/cropperjs@1.6.2/dist/cropper.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const profilePhotoModal = document.getElementById('profilePhotoModal');
            const profilePhotoThumb = document.getElementById('profilePhotoThumb');
            const profilePhotoPreview = document.getElementById('profilePhotoPreview');
            const input = document.getElementById('profilePhotoInput');
            const previewWrap = document.getElementById('photoPreviewWrap');
            const previewImg = document.getElementById('photoPreview');
            const openCropModalBtn = document.getElementById('openCropModal');
            const cropperImage = document.getElementById('cropperImage');
            const modalEl = document.getElementById('cropperModal');
            const zoomInBtn = document.getElementById('zoomIn');
            const zoomOutBtn = document.getElementById('zoomOut');
            const rotateLeftBtn = document.getElementById('rotateLeft');
            const applyCropBtn = document.getElementById('applyCrop');
            let cropper = null;
            let objectUrl = null;

            function destroyCropper() {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            }

            function revokeObjectUrl() {
                if (objectUrl) {
                    URL.revokeObjectURL(objectUrl);
                    objectUrl = null;
                }
            }

            if (input) {
                input.addEventListener('change', () => {
                    const file = input.files?.[0];
                    if (!file) return;

                    revokeObjectUrl();
                    objectUrl = URL.createObjectURL(file);
                    previewImg.src = objectUrl;
                    previewWrap.classList.remove('d-none');
                    openCropModalBtn.classList.remove('d-none');
                });
            }

            if (profilePhotoModal && profilePhotoPreview) {
                profilePhotoModal.addEventListener('show.bs.modal', () => {
                    if (profilePhotoThumb?.src) {
                        profilePhotoPreview.src = profilePhotoThumb.src;
                    }
                });
            }

            if (openCropModalBtn && modalEl && window.bootstrap) {
                const modal = new bootstrap.Modal(modalEl);

                openCropModalBtn.addEventListener('click', () => {
                    if (!previewImg.src) return;
                    cropperImage.src = previewImg.src;
                    modal.show();
                });

                modalEl.addEventListener('shown.bs.modal', () => {
                    destroyCropper();
                    cropper = new Cropper(cropperImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 1,
                        responsive: true,
                        background: false,
                        movable: true,
                        zoomable: true,
                        rotatable: true,
                        scalable: false,
                    });
                });

                modalEl.addEventListener('hidden.bs.modal', () => {
                    destroyCropper();
                });

                zoomInBtn?.addEventListener('click', () => cropper?.zoom(0.1));
                zoomOutBtn?.addEventListener('click', () => cropper?.zoom(-0.1));
                rotateLeftBtn?.addEventListener('click', () => cropper?.rotate(-90));

                applyCropBtn?.addEventListener('click', () => {
                    if (!cropper) return;
                    cropper.getCroppedCanvas({
                        width: 512,
                        height: 512,
                        imageSmoothingQuality: 'high'
                    }).toBlob((blob) => {
                        if (!blob) return;
                        const file = new File([blob], 'profile-photo.jpg', { type: 'image/jpeg' });
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        input.files = dataTransfer.files;

                        revokeObjectUrl();
                        objectUrl = URL.createObjectURL(file);
                        previewImg.src = objectUrl;
                        modal.hide();
                    }, 'image/jpeg', 0.9);
                });
            }
        });
    </script>
@endpush
