@extends('layouts.auth')

@section('title', 'Verifikasi OTP — EcoLend')

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
        max-width: 600px;
        display: flex;
        flex-direction: column;
        background: #ffffff;
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.12);
        padding: 48px 52px;
    }

    .brand {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-bottom: 40px;
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

    .brand-icon svg {
        width: 22px;
        height: 22px;
        fill: white;
    }

    .brand-name {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.3px;
    }

    .brand-name span {
        color: #f97316;
    }

    .form-header {
        text-align: center;
        margin-bottom: 36px;
    }

    .form-header h2 {
        font-size: 1.7rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.4px;
        margin-bottom: 12px;
    }

    .form-header p {
        font-size: 0.95rem;
        color: #64748b;
        line-height: 1.6;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        font-size: 0.8rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 10px;
        text-align: center;
    }

    .input-wrap input {
        width: 100%;
        height: 56px;
        padding: 0 20px;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.2rem;
        font-weight: 700;
        letter-spacing: 4px;
        color: #0f172a;
        background: #f8fafc;
        transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        outline: none;
        text-align: center;
    }

    .input-wrap input:focus {
        border-color: #f97316;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(249,115,22,0.10);
    }

    .btn-submit {
        width: 100%;
        height: 54px;
        background: linear-gradient(135deg, #ea580c, #f97316);
        color: white;
        border: none;
        border-radius: 14px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.2s, filter 0.2s;
        box-shadow: 0 4px 18px rgba(249,115,22,0.35);
        margin-bottom: 24px;
    }

    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 24px rgba(249,115,22,0.45);
        filter: brightness(1.05);
    }

    .alert-danger {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 0.85rem;
        color: #b91c1c;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 0.85rem;
        color: #15803d;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .resend-box {
        text-align: center;
        font-size: 0.9rem;
        color: #64748b;
    }

    .btn-resend {
        background: none;
        border: none;
        color: #f97316;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        padding: 0;
        text-decoration: none;
    }

    .btn-resend:hover {
        text-decoration: underline;
    }

    @media (max-width: 600px) {
        .page-wrap {
            padding: 32px 24px;
            box-shadow: none;
            border-radius: 0;
            min-height: 100vh;
            justify-content: center;
        }
    }
</style>

<div class="page-wrap">
    <div class="brand">
        <div class="brand-icon">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/>
            </svg>
        </div>
        <span class="brand-name">Eco<span>Lend</span></span>
    </div>

    <div class="form-header">
        <h2>Verifikasi Email</h2>
        <p>Kami telah mengirimkan 6 digit kode OTP ke email Anda. Silakan masukkan kode tersebut di bawah ini untuk memverifikasi akun Anda.</p>
    </div>

    @if ($errors->any())
        <div class="alert-danger">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert-success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('auth.verify_otp.process') }}">
        @csrf
        <div class="form-group">
            <label>Kode OTP</label>
            <div class="input-wrap">
                <input type="text" name="otp" placeholder="000000" maxlength="6" pattern="\d{6}" required autocomplete="off" autofocus>
            </div>
        </div>

        <button type="submit" class="btn-submit">Verifikasi Akun</button>
    </form>

    <div class="resend-box">
        Belum menerima email?
        <form method="POST" action="{{ route('auth.resend_otp') }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn-resend">Kirim Ulang OTP</button>
        </form>
    </div>
</div>

@endsection