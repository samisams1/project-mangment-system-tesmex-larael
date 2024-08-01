<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Models\Material;
use App\Models\MaterialCost;
use Illuminate\Http\Request;

class MaterialCostController extends Controller
{
    public function index()
    {
        $materials = MaterialCost::all();
        return view('materials.index', compact('materials'));
    }

    public function create()
    {
        $subtasks = Subtask::all();
        $materials = Material::all();
        // Show the form to create a new material
        return view('materialcosts.create', compact('subtasks', 'materials'));
     //   return view('materialcosts.create');
    }
    public function show($id)
    {
        // Retrieve the material cost data based on the provided $id
        $materialCosts = MaterialCost::where('subtask_id', $id)->get();
    
        // Iterate through each material cost and fetch the material name
   
        // Return the view with the material cost data
        return view('materialcosts.show', ['materialCosts' => $materialCosts]);
    }
    public function store(Request $request)
    {
        $materialCost = new MaterialCost;
        $materialCost->subtask_id = $request->subtask_id;
        $materialCost->material_id = $request->material_id;
        $materialCost->unit = $request->unit;
        $materialCost->qty = $request->qty;
        $materialCost->rate_with_vat = $request->rate_with_vat;
        $materialCost->amount = $request->amount;
        $materialCost->remark = $request->remark;
        $materialCost->save();

     //   return redirect()->route('materialcosts');

     $subtasks = Subtask::all();
     $materials = Material::all();
     // Show the form to create a new material
     return view('materialcosts.create', compact('subtasks', 'materials'));
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