<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SignupController extends Controller
{
    public function index(Request $request): View
    {
        $accountType = $request->query('type', 'buyer');
        return view('auth.register', ['accountType' => $accountType]);
    }

    public function submit(Request $request): RedirectResponse
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'account_type' => 'required|in:buyer,seller',
        ];

        if ($request->account_type === 'seller') {
            $validationRules['seller_type'] = 'required|in:particulier,zakelijk';
        }

        $request->validate($validationRules);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'account_type' => $request->account_type,
        ];

        if ($request->account_type === 'seller') {
            $userData['seller_type'] = $request->seller_type;
        }

        $user = User::create($userData);

        // Assign roles based on account type
        if ($request->account_type === 'buyer') {
            $user->assignRole('user');
        } else {
            $role = $request->seller_type === 'particulier' ? 'private_advertiser' : 'business_advertiser';
            $user->assignRole($role);
        }

        Auth::login($user);

        if ($user->hasRole('user')) {
            return redirect('/');
        }

        return redirect('seller/dashboard');
    }
}
