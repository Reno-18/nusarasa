<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChefRecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::where('user_id', auth()->id())->latest()->get();
        return view('chef.recipes.index', compact('recipes'));
    }

    public function create()
    {
        return view('chef.recipes.create');
    }

    public function store(StoreRecipeRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['source'] = 'local';
        $data['is_approved'] = false;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('recipes', 'public');
            $data['image_url'] = Storage::url($path);
        }

        Recipe::create($data);

        return redirect()->route('chef.dashboard')->with('success', 'Recipe created successfully. Waiting for admin approval.');
    }

    public function edit($id)
    {
        $recipe = Recipe::where('user_id', auth()->id())->findOrFail($id);
        return view('chef.recipes.edit', compact('recipe'));
    }

    public function update(UpdateRecipeRequest $request, $id)
    {
        $recipe = Recipe::where('user_id', auth()->id())->findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($recipe->image_url) {
                $oldPath = str_replace('/storage/', '', $recipe->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image')->store('recipes', 'public');
            $data['image_url'] = Storage::url($path);
        }

        $recipe->update($data);

        return redirect()->route('chef.dashboard')->with('success', 'Recipe updated successfully');
    }

    public function destroy($id)
    {
        $recipe = Recipe::where('user_id', auth()->id())->findOrFail($id);

        if ($recipe->image_url) {
            $oldPath = str_replace('/storage/', '', $recipe->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        $recipe->delete();

        return redirect()->route('chef.dashboard')->with('success', 'Recipe deleted successfully');
    }
}
