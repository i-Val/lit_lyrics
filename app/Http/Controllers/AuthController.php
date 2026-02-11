<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ResetPasswordMail;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();

            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended('/');
            }

            return back()->with('error', 'The provided credentials do not match our records.');
        
        } catch(Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

     public function registerForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = User::create([
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            event(new Registered($user));

            $user->sendEmailVerificationNotification();

            Auth::login($user);

            return redirect()->route('verification.notice')->with('status', 'verification-link-sent');
        } catch(Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

     public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(ForgotPasswordRequest $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();

        if ($user) {
            $token = Str::random(60);
            
            // Delete existing tokens
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            
            // Insert new token
            DB::table('password_reset_tokens')->insert([
                'email' => $email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]);

            Mail::to($email)->send(new ResetPasswordMail($token, $email));
        }

        return back()->with('status', 'We have e-mailed your password reset link!');
    }

     public function resetPasswordForm(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->query('email')]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
             return back()->withErrors(['email' => 'Invalid token or email.']);
        }

        // Check expiration (60 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'This password reset token has expired.']);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
             return back()->withErrors(['email' => 'We can\'t find a user with that e-mail address.']);
        }

        // Using hashed cast in User model, so we assign plain password
        $user->password = $request->password;
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Delete token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Your password has been reset!');
    }
}
