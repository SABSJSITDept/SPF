<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\SpfRegistration;
use App\Models\User;

class PasswordResetController extends Controller
{
    /**
     * Show the password reset form.
     */
    public function showForm()
    {
        return view('spf_frontend.password_reset');
    }

    /**
     * Handle password reset submission.
     * Finds the user by mobile number and updates their password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'mobile'   => ['required', 'digits:10', 'regex:/^[6-9]\d{9}$/'],
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'mobile.required'   => 'Mobile number is required.',
            'mobile.digits'     => 'Mobile number must be 10 digits.',
            'mobile.regex'      => 'Enter a valid Indian mobile number.',
            'password.required' => 'New password is required.',
            'password.min'      => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        // Check if mobile exists in SPF registrations
        $registration = SpfRegistration::where('mobile', $request->mobile)->first();

        if (! $registration) {
            return back()
                ->withInput($request->only('mobile'))
                ->withErrors(['mobile' => 'No account found with this mobile number.']);
        }

        // Find or create linked User account by mobile (stored as email surrogate)
        $user = User::where('email', $request->mobile . '@spf.local')->first();

        if (! $user) {
            return back()
                ->withInput($request->only('mobile'))
                ->withErrors(['mobile' => 'No login account found for this mobile number.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Your password has been changed successfully!');
    }
}
