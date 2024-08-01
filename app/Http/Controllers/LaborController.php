<?php

namespace App\Http\Controllers;

use App\Models\Labor;
use Illuminate\Http\Request;

class LaborController extends Controller
{
    public function index()
    {
        $materials = Labor::all();
        return view('labors.index', compact('materials'));
    }

    public function create()
    {
        // Show the form to create a new material
        return view('materials.create');
    }

    public function store(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'item' => 'required',
            'unit' => 'required',
            'quantity' => 'required|numeric',
            'rate_with_vat' => 'required|numeric',
            'amount' => 'required|numeric',
            'remark' => 'nullable',
        ]);

        // Create a new material
        $material = Material::create($validatedData);

        // Redirect to the index page with success message
        $materials = Material::all();
       return view('materials.index', compact('materials'));
        //return redirect()->route('materials.index', compact('materials'));
    }

    public function edit(Material $material)
    {
        // Show the form to edit the material
        return view('materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'item' => 'required',
            'unit' => 'required',
            'quantity' => 'required|numeric',
            'rate_with_vat' => 'required|numeric',
            'amount' => 'required|numeric',
            'remark' => 'nullable',
        ]);

        // Update the material
        $material->update($validatedData);

        // Redirect to the index page with success message
        return redirect()->route('materials.index')->with('success', 'Material updated successfully.');
    }

    public function destroy(Material $material)
    {
        // Delete the material
        $material->delete();

        // Redirect to the index page with success message
        return redirect()->route('materials.index')->with('success', 'Material deleted successfully.');
    }
}