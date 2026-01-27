<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Force set password flow
        if (is_null($user->password)) {
            return response()->json([
                'message' => 'Account not activated. Please set your password.',
                'code' => 'PASSWORD_REQUIRED'
            ], 403);
        }

        if (!Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('react-client')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return response()->json([
                'message' => __($status)
            ], 422);
        }

        return response()->json([
            'message' => 'Password set successfully. You can now login.'
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        // Do NOT reveal if user exists (security best practice)
        if (!$user) {
            return response()->json([
                'message' => 'If your email exists, a reset link has been sent.'
            ]);
        }

        // Create reset token
        $token = Password::broker()->createToken($user);

        // Build SPA reset URL
        $resetUrl = config('app.frontend_url')
            . "/reset-password?token={$token}&email=" . urlencode($user->email);

        // Send custom mail
        \Mail::to($user->email)->send(
            new \App\Mail\SetPasswordMail($resetUrl)
        );

        return response()->json([
            'message' => 'If your email exists, a reset link has been sent.'
        ]);
    }
}
