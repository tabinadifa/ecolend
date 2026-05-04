@extends('layouts.auth')

@section('title', 'Reset Password - EcoLend')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card auth-card">
            <div class="card-body">
                <h4 class="auth-title mb-2">Reset Password</h4>
                <p class="auth-description mb-4">Buat password baru untuk akun Anda.</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('auth.reset.process') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $email) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-auth-primary w-100">Reset Password</button>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('auth.login') }}" class="auth-link">Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
