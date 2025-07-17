<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Update the user's role.
     */
    public function updateRole(Request $request, User $user)
    {
        // only admin change the role
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'role' => ['required', Rule::in(['customer', 'admin', 'warehouse_keeper'])],
        ]);

        $user->update([
            'role' => $validated['role'],
        ]);

        return redirect()->route('dashboard')->with('status', 'role-updated');
    }
}
