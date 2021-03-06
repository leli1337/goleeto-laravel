<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use Illuminate\Foundation\Auth\ResetsPasswords;

class AuthController extends Controller
{


	use ResetsPasswords;
	/**
	 * Handle an authentication attempt.
	 *
	 * @return Response
	 */


	protected $redirectTo = '/';

	
	public function login(Request $request)
	{   $data = $request->only('username','password','page','id','id2');
		$username = $data['username'];
		$password = $data['password'];
		$page = $data['page'];
		$id = $data['id'];
		$id2 = $data['id2'];
		
		if (Auth::attempt(['username' => $username, 'password' => $password])) {
			if ($id==1) {
				return redirect()->intended(url('/').'/products');
			}else if($id==2 && $id2!=''){
				return redirect()->intended(url('/').'/user/'.$id2);
			}
			return redirect()->intended(strtolower(Auth::user()->type->type).'/dashboard');
		}else{
			return redirect()->intended('/');
		}
	}
	public function register(Request $request){
		$data = $request->only('firstname','lastname','username','password','email','birthdate','accType');

		$user = \App\User::create([
		   'UserName' => $data['username'],
		   'FirstName' => ucfirst($data['firstname']),
		   'LastName' => ucfirst($data['lastname']),
		   'email' => $data['email'],
		   'UserType' => $data['accType'],
		   'password' => bcrypt($data['password']),
	   ]);
		
		$userdetails = \App\UserDetail::create([
			'UserId' => $user->id,
			'birthdate' => $data['birthdate']
		]);

		if (Auth::attempt(['username' => $data['username'], 'password' => $data['password']])) {
			
			return redirect()->intended(strtolower(Auth::user()->type->type).'/dashboard');
		}

		return redirect()->intended('/');
	}

	public function logOut(){
		Auth::logout();
		return redirect()->intended('/');
	}


















}