<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Add this line to import Auth
use Illuminate\Support\Facades\Redirect; // Add this line to import Redirect
use App\Models\User;

class UserController extends Controller
{
    public function create(Request $request)
    {
        // Validate inputs
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|max:30',
            'cpassword' => 'required|min:5|max:30|same:password',
        ]);

        // Create a new User instance
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        // Save the user and check the result
        if ($user->save()) {
            return redirect()->back()->with('success', 'You are registered successfully');
        } else {
            return redirect()->back()->with('error', 'Something went wrong, failed to register');
        }
    }

    public function check(Request $request)
    {
        // Validate inputs
        $request->validate([
            'email' => 'required|email|exists:users,email', // Fix validation rule for email exists
            'password' => 'required|min:5|max:30',
        ], [
            'email.exists' => 'This email is not in the users table', // Fix typo 'failed' to 'fail'
        ]);

        $creds = $request->only('email','password');
        if (Auth::guard('web')->attempt($creds)) {
            return redirect()->route('user.home');
        } else {
            return Redirect::route('user.login')->with('fail', 'Incorrect credentials'); // Fixed Redirect class usage
        }
    }

    function logout()
    {
        Auth::guard('web')->logout();
        return redirect('/');
    }
}
