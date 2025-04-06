<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SignupController extends Controller
{
    public function index(Request $request): View
    {
        $accountType = $request->query('type', 'buyer');
        return view('public.auth.register', ['accountType' => $accountType]);
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

            if ($request->seller_type === 'zakelijk') {
                $validationRules['business_name'] = 'required|string|max:255';
            }
        }

        $request->validate($validationRules);

        try {
            DB::beginTransaction();

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

                // Create business if seller type is zakelijk
                if ($request->seller_type === 'zakelijk') {
                    $business = Business::create([
                        'name' => $request->business_name,
                        'user_id' => $user->id,
                    ]);

                    // Update user with business_id
                    $user->update(['business_id' => $business->id]);
                }
            }

            DB::commit();
            Auth::login($user);

            if ($user->hasRole('user')) {
                return redirect('/');
            }

            return redirect('seller/business');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Registration failed. Please try again.'])->withInput();
        }
    }
}
