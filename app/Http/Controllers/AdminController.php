<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalRecipes = Recipe::count();
        $pendingRecipes = Recipe::where('is_approved', false)->count();
        $totalChefs = User::where('role', 'chef')->count();

        return view('admin.dashboard', compact('totalUsers', 'totalRecipes', 'pendingRecipes', 'totalChefs'));
    }

    public function recipes(Request $request)
    {
        $query = Recipe::with('user')->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $recipes = $query->get();

        if ($request->ajax()) {
            return response()->json($recipes);
        }

        return view('admin.recipes', compact('recipes'));
    }

    public function approveRecipe($id)
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->update(['is_approved' => true]);

        if (request()->ajax()) {
            return response()->json(['message' => 'Resep berhasil disetujui!']);
        }

        return redirect()->back()->with('success', 'Recipe approved successfully');
    }

    public function destroyRecipe($id)
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->delete();

        if (request()->ajax()) {
            return response()->json(['message' => 'Resep berhasil dihapus!']);
        }

        return redirect()->back()->with('success', 'Recipe deleted successfully');
    }

    public function users(Request $request)
    {
        $query = User::latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->get();

        if ($request->ajax()) {
            return response()->json($users);
        }

        return view('admin.users', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update(['role' => $request->role]);

        if ($request->ajax()) {
            return response()->json(['message' => 'Peran pengguna berhasil diperbarui!']);
        }

        return redirect()->back()->with('success', 'User role updated successfully');
    }
}
