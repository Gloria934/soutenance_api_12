<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class NewPasswordController extends Controller
{
    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $request->validate([
                'token' => ['required', 'string'],
                'telephone' => ['required', 'string'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            // Explicitly define credentials to avoid including password_confirmation
            $credentials = [
                'telephone' => $request->telephone,
                'password' => $request->password,
                'token' => $request->token,
            ];

            // Attempt to reset the user's password
            $status = Password::reset(
                $credentials,
                function ($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($user));
                }
            );

            // Check the reset status
            if ($status === Password::PASSWORD_RESET) {
                return response()->json([
                    'status' => 'success',
                    'message' => __($status),
                ], 200);
            }

            // Handle failed reset attempts
            throw ValidationException::withMessages([
                'telephone' => [__($status)],
            ]);

        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (QueryException $e) {
            // Handle database query errors (e.g., column not found)
            \Log::error('Password reset database error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'A database error occurred while resetting the password.',
            ], 500);

        } catch (\Exception $e) {
            // Handle any other unexpected errors
            \Log::error('Password reset error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }
}