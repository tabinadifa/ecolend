<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Mail\OtpVerificationMail;

class AuthController extends Controller
{
    /* =======================
     * FORM
     * ======================= */
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

        public function forgotPasswordForm()
        {
            return view('auth.forgot-password');
        }

    /* =======================
     * LOGIN
     * ======================= */
    public function loginProcess(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
            'remember' => 'nullable'
        ]);

        $loginInput = trim($request->login);
        $remember = (bool) $request->remember;

        $loginFields = filter_var($loginInput, FILTER_VALIDATE_EMAIL)
            ? ['email']
            : ['username', 'npm'];

        $isAuthenticated = false;

        foreach ($loginFields as $field) {
            if (Auth::attempt([
                $field => $loginInput,
                'password' => $request->password,
            ], $remember)) {
                $isAuthenticated = true;
                break;
            }
        }

        if (!$isAuthenticated) {
            return back()->withErrors([
                'login' => 'Username / Email atau password salah'
            ])->withInput();
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Check verification
        if (is_null($user->email_verified_at)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Set session for OTP and redirect
            session(['verify_email' => $user->email]);
            return redirect()->route('auth.verify_otp')->withErrors([
                'login' => 'Email Anda belum diverifikasi. Silakan masukkan kode OTP yang dikirim ke email Anda.',
            ]);
        }

        if ($user instanceof User) {
            $user->last_active_at = Carbon::now();
            $user->save();
        }

        return redirect()->route('dashboard')->with('success', 'Login berhasil');
    }

        public function sendResetLink(Request $request)
        {
            $request->validate([
                'email' => ['required', 'email'],
            ]);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
            }

            return back()->withErrors([
                'email' => 'Email tidak ditemukan atau gagal mengirim tautan reset.',
            ]);
        }

        public function resetPasswordForm(string $token)
        {
            return view('auth.reset-password', [
                'token' => $token,
                'email' => request('email'),
            ]);
        }

        public function resetPasswordProcess(Request $request)
        {
            $request->validate([
                'token' => ['required'],
                'email' => ['required', 'email'],
                'password' => ['required', 'confirmed', RulesPassword::defaults()],
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                    ])->setRememberToken(Str::random(60));

                    $user->save();

                    event(new PasswordReset($user));
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return redirect()->route('auth.login')->with('success', 'Password berhasil direset. Silakan login.');
            }

            return back()->withErrors([
                'email' => 'Token reset tidak valid atau sudah kedaluwarsa.',
            ]);
        }

    /* =======================
     * REGISTER
     * ======================= */
    public function registerProcess(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'username' => 'required|string|max:100|unique:users',
            'npm'      => 'required|string|max:100|unique:users',
            'program_studi' => 'required|string|max:255',
            'no_telp' => 'required|string|max:100',
            'password' => ['required', 'confirmed', RulesPassword::defaults()],
        ], [
            'email.unique' => 'Email sudah terdaftar, silakan gunakan email lain.',
            'username.unique' => 'Username sudah terdaftar, silakan gunakan username lain',
            'npm' => 'NPM sudah terdaftar'
        ]);

        $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'username'       => $request->username,
            'npm'            => $request->npm,
            'program_studi'  => $request->program_studi,
            'no_telp'        => $request->no_telp,
            'password'       => Hash::make($request->password),
            'otp_code'       => $otpCode,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
            'role'           => 'peminjam',
            'is_active'      => true,
            'last_active_at' => Carbon::now(),
        ]);

        try {
            Mail::to($user->email)->send(new OtpVerificationMail($otpCode, $user->name));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail Error: ' . $e->getMessage());
        }

        // Simpan email ke session untuk proses verifikasi
        session(['verify_email' => $user->email]);

        return redirect()->route('auth.verify_otp')->with('success', 'Registrasi berhasil. Silakan cek email Anda untuk kode OTP.');
    }

    /* =======================
     * OTP VERIFICATION
     * ======================= */
    public function verifyOtpForm()
    {
        if (!session('verify_email')) {
            return redirect()->route('auth.login')->withErrors(['login' => 'Silakan login atau daftar akun terlebih dahulu.']);
        }
        return view('auth.verify-otp');
    }

    public function verifyOtpProcess(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        $email = session('verify_email');
        if (!$email) {
            return redirect()->route('auth.login')->withErrors(['login' => 'Sesi kedaluwarsa, silakan login kembali.']);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('auth.register')->withErrors(['email' => 'User tidak ditemukan.']);
        }

        if ($user->otp_code !== $request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.']);
        }

        if ($user->otp_expires_at < Carbon::now()) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa. Silakan minta kode baru.']);
        }

        // Verifikasi berhasil
        $user->email_verified_at = Carbon::now();
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        session()->forget('verify_email');

        // Opsional: Langsung loginkan user
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Email berhasil diverifikasi.');
    }

    public function resendOtp()
    {
        $email = session('verify_email');
        if (!$email) {
            return redirect()->route('auth.login');
        }

        $user = User::where('email', $email)->first();
        if ($user) {
            $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $user->otp_code = $otpCode;
            $user->otp_expires_at = Carbon::now()->addMinutes(10);
            $user->save();

            try {
                Mail::to($user->email)->send(new OtpVerificationMail($otpCode, $user->name));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Resend Mail Error: ' . $e->getMessage());
                return back()->withErrors(['otp' => 'Gagal mengirim email, silakan coba lagi. Error: ' . $e->getMessage()]);
            }
        }

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }

    /* =======================
     * LOGOUT
     * ======================= */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login')->with('success', 'Logout berhasil');
    }
}
