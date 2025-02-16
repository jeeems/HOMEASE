<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /**
     * Handle the incoming registration request.
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'role' => 'required|in:client,worker',
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'birth_month' => 'required|integer|min:1|max:12',
                'birth_day' => 'required|integer|min:1|max:31',
                'birth_year' => 'required|integer|min:1900|max:' . (date('Y') - 18),
                'gender' => 'required|in:male,female',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'required|string|max:15|unique:users',
                'street' => 'required|string|max:255',
                'barangay' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'zip_code' => 'required|string|max:10',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $birthdate = "{$request->birth_year}-{$request->birth_month}-{$request->birth_day}";

            $user = User::create([
                'role' => $request->role,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'birthdate' => $birthdate,
                'gender' => $request->gender,
                'email' => $request->email,
                'phone' => $request->phone,
                'street' => $request->street,
                'barangay' => $request->barangay,
                'city' => $request->city,
                'zip_code' => $request->zip_code,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            Auth::login($user);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registration successful',
                    'redirect' => route('verification.notice')
                ]);
            }

            return redirect()->route('verification.notice');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registration failed: ' . $e->getMessage()
                ], 422);
            }
            throw $e;
        }
    }

    public function checkEmail(Request $request)
    {
        $exists = User::where('email', $request->value)->exists();
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? "Your email is already registered. <a href='" . route('login') . "'>Do you want to log in?</a>" : ""
        ]);
    }

    public function checkPhone(Request $request)
    {
        $exists = User::where('phone', $request->value)->exists();
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? "This phone number is already registered." : ""
        ]);
    }
}
