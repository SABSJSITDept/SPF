<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    private array $anchalList = [
        'Bikaner-Marwar', 'Jaipur', 'Jodhpur', 'Udaipur', 'Ajmer',
        'Kota', 'Bharatpur', 'Alwar', 'Sikar', 'Nagaur',
        'Pali', 'Barmer', 'Sirohi', 'Jaisalmer',
    ];

    public function index()
    {
        // Fetch distinct anchals from registrations too
        $anchalsFromDB = \App\Models\SpfRegistration::distinct()->pluck('anchal')->filter()->sort()->values();
        $anchalList    = $anchalsFromDB->isNotEmpty() ? $anchalsFromDB : collect($this->anchalList);

        $admins = Admin::where('id', '!=', Auth::guard('admin')->id())
                       ->orderBy('role')
                       ->orderBy('name')
                       ->get();

        return view('spf_backend.users.index', compact('admins', 'anchalList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
            'role'     => ['required', Rule::in(['operator', 'anchal_operator'])],
            'anchal'   => 'required_if:role,anchal_operator|nullable|string|max:100',
        ], [
            'anchal.required_if' => 'Anchal field is required for Anchal Operator role.',
        ]);

        Admin::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'anchal'   => $request->role === 'anchal_operator' ? $request->anchal : null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Admin user created successfully.');
    }

    public function destroy(Admin $admin)
    {
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Super Admin cannot be deleted.');
        }

        $admin->delete();
        return redirect()->route('admin.users.index')->with('success', 'Admin user deleted.');
    }
}
