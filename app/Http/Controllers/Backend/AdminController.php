<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::guard('admin')->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        return view('admin.login');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // return redirect('/');
        return redirect()->intended(route('admin.login'))->with('success', 'Admin logout successfully');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }
    public function profile()
    {
        return 'profile';
    }


    // public function logout(Request $request): RedirectResponse
    // {
    //     Auth::guard('web')->logout();

    //     // $request->session()->invalidate();

    //     // $request->session()->regenerateToken();

    //     return redirect()->intended(route('admin.login'))
    //                     ->with('success', 'Admin logout in successfully');
    // }

}
