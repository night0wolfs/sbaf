<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request): Redirector|RedirectResponse|Application
    {
        if (Auth::check()) {
            return redirect((route('welcome')));
        }
        $formFields = $request->only(['email', 'password']);
        if (Auth::attempt($formFields)) {
            return redirect()->intended(route('welcome'));
        }

        return redirect(route('auth.login'))->withErrors([
            'email' => 'No login'
        ]);
    }
}
