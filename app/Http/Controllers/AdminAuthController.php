<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
	public function showLogin()
	{
		if (Auth::guard('admin')->check()) {
			return redirect()->route('admin.dashboard');
		}
		return view('admin.auth.login');
	}

	public function login(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'mobile' => ['required','string','regex:/^\d{10,15}$/'],
			'password' => ['required','string','min:6'],
		]);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput();
		}

		$remember = (bool) $request->boolean('remember');
		$credentials = [
			'mobile' => $request->input('mobile'),
			'password' => $request->input('password'),
		];
		if (Auth::guard('admin')->attempt($credentials, $remember)) {
			$request->session()->regenerate();
			return redirect()->intended(route('admin.dashboard'));
		}

		return back()->withErrors(['mobile' => 'شماره موبایل یا گذرواژه نادرست است.'])->withInput();
	}

	public function logout(Request $request)
	{
		Auth::guard('admin')->logout();
		$request->session()->invalidate();
		$request->session()->regenerateToken();
		return redirect()->route('admin.login');
	}
}
