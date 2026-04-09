<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForcedPasswordController extends Controller
{
    
    public function show()
    {
        return view('auth.force-change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[\W_]).+$/'
            ],
        ], [
            'password.regex' => 'Password must contain at least one letter, one number, and one symbol.',
        ]);

        $user = auth()->user();

        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->password_changed_at = now();
        $user->save();

        if ($user->isSacdev()) {
            return redirect()->route('admin.home')->with('status', 'Password updated.');
        }

        return redirect()->route('org.home')->with('status', 'Password updated.');
    }
}