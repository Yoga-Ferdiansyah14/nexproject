<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        $totalUsers = $users->count();
        return view('users.index', compact('users', 'totalUsers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => ['required', Rule::in(['direktur', 'marketing', 'team_leader', 'tenaga_ahli', 'admin'])],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['direktur', 'marketing', 'team_leader', 'tenaga_ahli', 'admin'])],
        ]);

        $user->update($validated);

        return back()->with('success', 'Role pengguna berhasil diperbarui.');
    }
}
