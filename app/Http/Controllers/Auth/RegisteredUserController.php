<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
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

        public function showForm()
    {
        $hospitals = Hospital::all();
        return view('auth.register', compact('hospitals'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'hospital' => ['required', 'exists:hospitals,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'hospital_id' => $request->hospital,
        ]);

        event(new Registered($user));

        Auth::login($user);

       // Check the hospital and redirect accordingly
            if ($user->hospital_id == 1) {
                return redirect()->route('dashboard');
            } elseif ($user->hospital_id == 2) {
                return redirect()->route('hospital2.dashboard');
            } else {
                return redirect()->route('error.page')->with('error', 'Invalid hospital ID.');
            }
    }
}
