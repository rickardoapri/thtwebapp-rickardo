<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
	public function register()
	{
		return view('auth/register');
	}

	public function registerSimpan(Request $request)
	{
		//validasi data register
		Validator::make($request->all(), [
			'nama' => 'required',
			'email' => 'required|email',
			'password' => 'required|confirmed|min:8|regex:/[0-9]/'
		])->validate();

		//craete data register
		User::create([
			'nama' => $request->nama,
			'email' => $request->email,
			'password' => Hash::make($request->password),
			'level' => 'Admin'
		]);

		return redirect()->route('login')
		->with('success', 'Pendaftaran berhasil silahkan login.');
	}

	public function login()
	{
		return view('auth/login');
	}

	public function loginAksi(Request $request)
	{
		//validasi data login
		Validator::make($request->all(), [
			'email' => 'required|email',
			'password' => 'required'
		])->validate();

		//remember data login
		if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
			throw ValidationException::withMessages([
				'email' => trans('auth.failed')
			]);
		}

		//session user login
		$request->session()->regenerate();

		return redirect()->route('dashboard')
		->with('success', 'Selamat anda berhasil login.');
	}

	public function logout(Request $request)
	{
		//perintah logout
		Auth::guard('web')->logout();

		$request->session()->invalidate();

		return redirect('login')
		->with('success', 'Terimakasih, anda sudah logout.');
	}
}
