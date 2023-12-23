<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class AdminController extends Controller
{
    function check(Request $request)
    {
        //validate inputs

        $request->validate
        ([
            "email"=> "required|email|exists:admins,email",
            "password"=> "required|min:5|max:30"
        ],[
            "email.exists"=> "This email does not exist in admins table",
        ]);

        $creds = $request->only("email","password");

        if(Auth::guard("admin")->attempt($creds))
        {
            return redirect()->route("admin.home");
        }
        else
        {
            return redirect()->route("admin.login")->with('fail','Incorrect Credentials');
        }
    }
}
