@extends('layouts.auth')

@section('title', 'Lupa Password - EcoLend')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card auth-card">
            <div class="card-body">
                <h4 class="auth-title mb-2">Lupa Password</h4>
                <p class="auth-description mb-4">Masukkan email akun Anda untuk menerima link reset password.</p>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first('email') ?? 'Terjadi kesalahan. Silakan coba lagi.' }}
                    </div>
                @endif

                <form method="POST" action="{{ route('auth.forgot.process') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
                    </div>
                    <button type="submit" class="btn btn-auth-primary w-100">Kirim Link Reset</button>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('auth.login') }}" class="auth-link">Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
