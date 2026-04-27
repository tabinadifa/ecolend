@extends('layouts.auth')

@section('title', 'Daftar — EcoLend')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #f8fafc;
        min-height: 100vh;
        padding: 20px;
    }

    .page-wrap {
        min-height: calc(100vh - 40px);
        max-width: 1280px;
        margin: 0 auto;
        display: flex;
        align-items: stretch;
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.12);
    }

    /* ── LEFT ACCENT ── */
    .left-accent {
        width: 42%;
        background: linear-gradient(160deg, #0f1117 0%, #1a1f2e 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 60px 52px;
        position: relative;
        overflow: hidden;
    }

    .left-accent::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 60% 50% at 110% 10%, rgba(249,115,22,0.20) 0%, transparent 60%),
            radial-gradient(ellipse 50% 60% at -10% 90%, rgba(251,191,36,0.12) 0%, transparent 60%);
        pointer-events: none;
    }

    .accent-grid {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 52px;
        position: relative;
        z-index: 1;
    }

    .brand-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #f97316, #fbbf24);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .brand-icon img {
        width: 28px;
        height: 28px;
        object-fit: contain;
    }

    .brand-name {
        font-size: 1.4rem;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: -0.3px;
    }

    .brand-name span { color: #f97316; }

    .accent-content {
        position: relative;
        z-index: 1;
    }

    .accent-content h2 {
        font-size: 2.1rem;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.2;
        letter-spacing: -0.5px;
        margin-bottom: 16px;
    }

    .accent-content h2 .highlight {
        background: linear-gradient(90deg, #f97316, #fbbf24);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .accent-content p {
        font-size: 0.9rem;
        color: #94a3b8;
        line-height: 1.75;
        margin-bottom: 36px;
    }

    .step-list {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .step-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }

    .step-num {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: rgba(249,115,22,0.15);
        border: 1px solid rgba(249,115,22,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 800;
        color: #f97316;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .step-info p {
        font-size: 0.83rem;
        font-weight: 600;
        color: #e2e8f0;
        margin-bottom: 2px;
    }

    .step-info span {
        font-size: 0.75rem;
        color: #64748b;
    }

    /* ── RIGHT FORM ── */
    .right-form {
        flex: 1;
        background: #ffffff;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 48px 52px;
        overflow-y: auto;
    }

    .form-container {
        width: 100%;
        max-width: 480px;
        padding-top: 4px;
    }

    .form-header {
        margin-bottom: 30px;
    }

    .form-header h2 {
        font-size: 1.65rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.4px;
        margin-bottom: 5px;
    }

    .form-header p {
        font-size: 0.875rem;
        color: #94a3b8;
    }

    .section-divider {
        font-size: 0.72rem;
        font-weight: 700;
        color: #cbd5e1;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 22px 0 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-divider::before,
    .section-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #f1f5f9;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .input-wrap {
        position: relative;
    }

    .input-wrap .input-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        stroke: #94a3b8;
        fill: none;
        pointer-events: none;
        transition: stroke 0.2s;
    }

    .input-wrap input,
    .input-wrap select {
        width: 100%;
        height: 44px;
        padding: 0 13px 0 40px;
        border: 1.5px solid #e2e8f0;
        border-radius: 11px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.875rem;
        color: #0f172a;
        background: #f8fafc;
        transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        outline: none;
        appearance: none;
    }

    .input-wrap select {
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        padding-right: 36px;
    }

    .input-wrap input:focus,
    .input-wrap select:focus {
        border-color: #f97316;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.09);
    }

    .input-wrap:focus-within .input-icon {
        stroke: #f97316;
    }

    .btn-register {
        width: 100%;
        height: 50px;
        background: linear-gradient(135deg, #ea580c, #f97316);
        color: white;
        border: none;
        border-radius: 12px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.2s, filter 0.2s;
        box-shadow: 0 4px 18px rgba(249,115,22,0.35);
        margin-top: 6px;
    }

    .btn-register:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 24px rgba(249,115,22,0.45);
        filter: brightness(1.05);
    }

    .btn-register:active {
        transform: translateY(0);
    }

    .auth-footer {
        text-align: center;
        margin-top: 18px;
        font-size: 0.84rem;
        color: #64748b;
    }

    .auth-footer a {
        color: #f97316;
        font-weight: 700;
        text-decoration: none;
    }

    .auth-footer a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        body { padding: 0; }
        .page-wrap {
            min-height: 100vh;
            border-radius: 0;
            box-shadow: none;
        }
        .left-accent { display: none; }
        .right-form { padding: 32px 24px; }
        .form-row { grid-template-columns: 1fr; gap: 0; }
    }
</style>

<div class="page-wrap">

    {{-- LEFT --}}
    <div class="left-accent">
        <div class="accent-grid"></div>

        <div class="brand">
            <div class="brand-icon">
                <img src="{{ asset('storage/uploads/logo/EcoLend.png') }}" alt="EcoLend Logo">
            </div>
            <span class="brand-name">Eco<span>Lend</span></span>
        </div>

        <div class="accent-content">
            <h2>Daftar & mulai<br>pinjam <span class="highlight">kapan saja.</span></h2>
            <p>Buat akun dalam 1 menit dan nikmati kemudahan meminjam fasilitas kampus secara digital.</p>

            <div class="step-list">
                <div class="step-item">
                    <div class="step-num">1</div>
                    <div class="step-info">
                        <p>Isi data diri Anda</p>
                        <span>Nama, NPM, email, dan program studi</span>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">2</div>
                    <div class="step-info">
                        <p>Buat kredensial login</p>
                        <span>Username dan password yang aman</span>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">3</div>
                    <div class="step-info">
                        <p>Mulai meminjam fasilitas</p>
                        <span>Ajukan pinjaman kapan dan di mana saja</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="right-form">
        <div class="form-container">
            <div class="form-header">
                <h2>Buat akun baru</h2>
                <p>Bergabunglah dan kelola peminjaman fasilitas dengan lebih efisien.</p>
            </div>

            <form method="POST" action="{{ route('auth.register.process') }}">
                @csrf

                {{-- Nama --}}
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" stroke-width="1.8"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <input type="text" name="name" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required>
                    </div>
                </div>

                <div class="section-divider">Info Akademik</div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <div class="input-wrap">
                            <svg class="input-icon" viewBox="0 0 24 24" stroke-width="1.8"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <input type="email" name="email" placeholder="email@gmail.com" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>NPM</label>
                        <div class="input-wrap">
                            <svg class="input-icon" viewBox="0 0 24 24" stroke-width="1.8"><path d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <input type="text" name="npm" placeholder="Nomor Pokok Mahasiswa" value="{{ old('npm') }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Program Studi</label>
                        <div class="input-wrap">
                            <svg class="input-icon" viewBox="0 0 24 24" stroke-width="1.8"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <select name="program_studi" required>
                                <option value="">Pilih Prodi</option>
                                <option value="Manajemen" {{ old('program_studi') == 'Manajemen' ? 'selected' : '' }}>Manajemen</option>
                                <option value="Akuntansi" {{ old('program_studi') == 'Akuntansi' ? 'selected' : '' }}>Akuntansi</option>
                                <option value="Bisnis Digital" {{ old('program_studi') == 'Bisnis Digital' ? 'selected' : '' }}>Bisnis Digital</option>
                                <option value="Perdagangan Internasional" {{ old('program_studi') == 'Perdagangan Internasional' ? 'selected' : '' }}>Perdagangan Internasional</option>
                                <option value="Perbankan dan Keuangan Digital" {{ old('program_studi') == 'Perbankan dan Keuangan Digital' ? 'selected' : '' }}>Perbankan & Keuangan Digital</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nomor Telepon</label>
                        <div class="input-wrap">
                            <svg class="input-icon" viewBox="0 0 24 24" stroke-width="1.8"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <input type="text" name="no_telp" placeholder="0812xxxxxxxx" value="{{ old('no_telp') }}" required>
                        </div>
                    </div>
                </div>

                <div class="section-divider">Kredensial Akun</div>

                <div class="form-group">
                    <label>Username</label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" stroke-width="1.8"><path d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <input type="text" name="username" placeholder="Masukkan username" value="{{ old('username') }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Password</label>
                        <div class="input-wrap">
                            <svg class="input-icon" viewBox="0 0 24 24" stroke-width="1.8"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <input type="password" name="password" placeholder="min. 8 karakter" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <div class="input-wrap">
                            <svg class="input-icon" viewBox="0 0 24 24" stroke-width="1.8"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <input type="password" name="password_confirmation" placeholder="ulangi password" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-register">Buat Akun Sekarang</button>

                <div class="auth-footer">
                    Sudah punya akun? <a href="{{ route('auth.login') }}">Masuk di sini</a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        const pw = form.querySelector('input[name="password"]').value;
        const pw2 = form.querySelector('input[name="password_confirmation"]').value;
        if (pw !== pw2) {
            e.preventDefault();
            Swal.fire({
                title: 'Password tidak cocok',
                text: 'Konfirmasi password harus sama dengan password yang dibuat.',
                icon: 'error',
                confirmButtonColor: '#f97316',
                confirmButtonText: 'Coba lagi'
            });
        }
    });

    const emailError = @json($errors->first('email'));
    if (emailError) {
        Swal.fire({
            title: 'Email sudah terdaftar',
            text: emailError,
            icon: 'error',
            confirmButtonColor: '#f97316'
        });
    }
});
</script>
@endpush