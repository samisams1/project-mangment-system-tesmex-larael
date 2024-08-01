<?php

namespace App\Http\Controllers;
use App\Models\LaborCost;
use App\Models\Subtask;
use App\Models\Material;
use Illuminate\Http\Request;

class LaborCostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subtasks = Subtask::all();
        $materials = Material::all();
        // Show the form to create a new material
        return view('laborcosts.create', compact('subtasks', 'materials'));

       // return view('laborcosts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $materialCost = new LaborCost;
        $materialCost->subtask_id = $request->subtask_id;
        $materialCost->labor_id = $request->labor_id;
        $materialCost->unit = $request->unit;
        $materialCost->qty = $request->qty;
        $materialCost->rate_with_vat = $request->rate_with_vat;
        $materialCost->amount = $request->amount;
        $materialCost->remark = $request->remark;
        $materialCost->save();

        return redirect()->route('materialcosts');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      // Retrieve the material cost data based on the provided $id
      $laborcosts = LaborCost::where('subtask_id', $id)->get();
      // Return the view with the material cost data
      return view('laborcosts.show', ['laborCosts' => $laborcosts]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
