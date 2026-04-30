@extends('layouts.auth')

@section('title', 'Login — EcoLend')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #ffffff;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .page-wrap {
        width: 100%;
        min-height: calc(100vh - 40px);
        max-width: 1280px;
        display: flex;
        align-items: stretch;
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.12);
    }

    /* ── LEFT PANEL ── */
    .left-panel {
        width: 45%;
        background: linear-gradient(145deg, #1a1f2e 0%, #0f1117 60%);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 48px 52px;
        position: relative;
        overflow: hidden;
    }

    .left-panel::before {
        content: '';
        position: absolute;
        width: 420px;
        height: 420px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(249,115,22,0.18) 0%, transparent 70%);
        top: -80px;
        right: -120px;
        pointer-events: none;
    }

    .left-panel::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(251,191,36,0.10) 0%, transparent 70%);
        bottom: 40px;
        left: -60px;
        pointer-events: none;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 1;
    }

    .brand-icon {
        width: 42px;
        height: 42px;
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
        font-size: 1.35rem;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: -0.3px;
    }

    .brand-name span {
        color: #f97316;
    }

    .hero-text {
        position: relative;
        z-index: 1;
    }

    .hero-text h1 {
        font-size: 2.4rem;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.2;
        letter-spacing: -0.5px;
        margin-bottom: 18px;
    }

    .hero-text h1 span {
        background: linear-gradient(90deg, #f97316, #fbbf24);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-text p {
        font-size: 0.95rem;
        color: #8b94a7;
        line-height: 1.7;
        max-width: 320px;
    }

    .feature-pills {
        display: flex;
        flex-direction: column;
        gap: 14px;
        position: relative;
        z-index: 1;
    }

    .pill {
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 14px;
        padding: 14px 18px;
    }

    .pill-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(249,115,22,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .pill-icon svg {
        width: 17px;
        height: 17px;
        stroke: #f97316;
        fill: none;
    }

    .pill-text p {
        font-size: 0.82rem;
        font-weight: 600;
        color: #e2e8f0;
        margin-bottom: 2px;
    }

    .pill-text span {
        font-size: 0.75rem;
        color: #64748b;
    }

    /* ── RIGHT PANEL ── */
    .right-panel {
        flex: 1;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 48px 52px;
    }

    .form-container {
        width: 100%;
        max-width: 400px;
    }

    .form-header {
        margin-bottom: 36px;
    }

    .form-header h2 {
        font-size: 1.7rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.4px;
        margin-bottom: 6px;
    }

    .form-header p {
        font-size: 0.875rem;
        color: #94a3b8;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        font-size: 0.78rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 7px;
    }

    .input-wrap {
        position: relative;
    }

    .input-wrap .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 17px;
        height: 17px;
        stroke: #94a3b8;
        fill: none;
        pointer-events: none;
        transition: stroke 0.2s;
    }

    .input-wrap input {
        width: 100%;
        height: 48px;
        padding: 0 14px 0 42px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.9rem;
        color: #0f172a;
        background: #f8fafc;
        transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        outline: none;
    }

    .input-wrap input:focus {
        border-color: #f97316;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(249,115,22,0.10);
    }

    .input-wrap input:focus + .focus-ring {
        opacity: 1;
    }

    .input-wrap input:focus ~ .input-icon,
    .input-wrap:focus-within .input-icon {
        stroke: #f97316;
    }

    .alert-danger {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 10px;
        padding: 11px 14px;
        font-size: 0.82rem;
        color: #b91c1c;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-login {
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
        letter-spacing: 0.2px;
        margin-top: 8px;
    }

    .btn-login:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 24px rgba(249,115,22,0.45);
        filter: brightness(1.05);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 22px 0;
    }

    .divider hr {
        flex: 1;
        border: none;
        border-top: 1px solid #e2e8f0;
    }

    .divider span {
        font-size: 0.75rem;
        color: #94a3b8;
        font-weight: 500;
    }

    .auth-footer {
        text-align: center;
        font-size: 0.85rem;
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
        .left-panel { display: none; }
        .right-panel { padding: 32px 24px; }
    }
</style>

<div class="page-wrap">

    {{-- LEFT --}}
    <div class="left-panel">
        <div class="brand">
            <div class="brand-icon">
                <img src="{{ asset('storage/uploads/logo/EcoLend.png') }}" alt="EcoLend Logo">
            </div>
            <span class="brand-name">Eco<span>Lend</span></span>
        </div>

        <div class="hero-text">
            <h1>Pinjam fasilitas<br>kampus dengan<br><span>mudah & cepat.</span></h1>
            <p>Platform peminjaman alat dan fasilitas Fakultas Ekonomi dan Bisnis yang terintegrasi dan efisien.</p>
        </div>

        <div class="feature-pills">
            <div class="pill">
                <div class="pill-icon">
                    <svg viewBox="0 0 24 24" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="pill-text">
                    <p>Proses Cepat & Mudah</p>
                    <span>Ajukan peminjaman dalam hitungan menit</span>
                </div>
            </div>
            <div class="pill">
                <div class="pill-icon">
                    <svg viewBox="0 0 24 24" stroke-width="2"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="pill-text">
                    <p>Aman & Terpercaya</p>
                    <span>Data dan riwayat pinjaman tersimpan aman</span>
                </div>
            </div>
            <div class="pill">
                <div class="pill-icon">
                    <svg viewBox="0 0 24 24" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="pill-text">
                    <p>Kelola Riwayat Pinjaman</p>
                    <span>Pantau status peminjaman secara real-time</span>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="right-panel">
        <div class="form-container">
            <div class="form-header">
                <h2>Selamat datang</h2>
                <p>Masuk ke akun EcoLend Anda untuk melanjutkan.</p>
            </div>

            @if ($errors->any())
                <div class="alert-danger">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ $errors->first('login') ?? 'Login gagal, periksa kembali data Anda.' }}
                </div>
            @endif

            <form method="POST" action="{{ route('auth.login.process') }}">
                @csrf

                <div class="form-group">
                    <label>Email/Username/NPM</label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" stroke-width="1.8"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <input type="text" name="login" value="{{ old('login') }}" placeholder="Masukkan email / username / npm " required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" stroke-width="1.8"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <input type="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn-login">Masuk ke Akun</button>

                <div class="divider">
                    <hr><span>atau</span><hr>
                </div>

                <div class="auth-footer">
                    Belum punya akun? <a href="{{ route('auth.register') }}">Daftar sekarang</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection