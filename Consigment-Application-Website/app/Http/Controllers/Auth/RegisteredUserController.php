<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi semua input, termasuk yang baru
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'nim' => ['required', 'string', 'max:20', 'unique:'.User::class],
            'phone_number' => ['required', 'string', 'max:15'],
            'ktm_photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Validasi foto
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Handle upload foto KTM
        $ktmPath = $request->file('ktm_photo')->store('ktm_photos', 'public');

        // 3. Buat user baru dengan semua data
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nim' => $request->nim,
            'phone_number' => $request->phone_number,
            'ktm_photo_path' => $ktmPath,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}