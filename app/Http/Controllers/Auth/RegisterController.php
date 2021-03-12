<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function save(Request $request): Redirector|RedirectResponse|Application
    {
        if (Auth::check()) {
            return redirect((route('welcome')));
        }
        $validateFields = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (User::where('email', $validateFields['email'])->exists()) {
            return redirect(route('auth.registration'))->withErrors([
                'email'=> 'Email register'
            ]);
        }

        $user = User::create($validateFields);
        if ($user) {
            Auth::login($user);
            return redirect((route('welcome')));
        }
        return redirect(route('auth.login'))->withErrors([
            'formError' => 'Error'
        ]);
    }
}
