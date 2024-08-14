<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Models\Material;
use App\Models\MaterialCost;
use Illuminate\Http\Request;
use App\Models\UnitMeasure;
use App\Models\User;
use App\Models\Workspace;
use App\Models\Client;
use App\Models\Project;
class MaterialCostController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // fetch session and use it in entire class with constructor
            $this->workspace = Workspace::find(session()->get('workspace_id'));
            $this->user = getAuthenticatedUser();
            return $next($request);
        });
    }

    public function index()
    {
        $materials = MaterialCost::all();
        return view('materials.index', compact('materials'));
    }
    public function materialcostsSelect(Request $request)
    {
        $query = Material::with('UnitMeasure');
    
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->get('search', ['value' => '']);
        $order = $request->get('order', [['column' => 0, 'dir' => 'asc']]);
    
        $totalRecords = $query->count();
    
        $materials = $query
            ->skip($start)
            ->take($length)
            ->orderBy('id', 'desc')
            ->get();
    
        $data = $materials->map(function ($material) {
            return [
                'id' => $material->id,
                'item' => $material->name,
                'unit' => $material->UnitMeasure->name,
                'quantity' => $material->quantity,
                'rate_with_vat' => $material->rate_with_vat,
                'amount' => $material->quantity * $material->rate_with_vat,
            ];
        });
    
        return view('materialcosts.materialcostsSelect', [
            'materials' => $materials
        ]);
    }

    public function anotherPage(Request $request)
    {
        $materialIds = $request->input('material_ids', []);
        $selectedMaterials = Material::whereIn('id', $materialIds)->get();
        // Perform additional logic or return a view
        return view('another-page', compact('selectedMaterials'));
    }
    public function materialSelection(Request $request)
    {
        $selectedMaterials = $request->input('selected_materials', []);
    
        $materials = [];
        foreach ($selectedMaterials as $serializedMaterial) {
            $material = json_decode($serializedMaterial, true);
            $materials[] = $material;
        }
    
        return view('materialcosts.material-subtasks', ['selectedMaterials' => $materials]);
    }
public function materialSelectionSubmit(Request $request)
{
    $selectedMaterials = $request->input('selected_materials', []);

    // Deserialize the selected materials
    $selectedMaterialsData = array_map('unserialize', $selectedMaterials);

    return view('materialcosts.material-subtasks', ['selectedMaterials' => $selectedMaterialsData]);
}
    public function create()
    {
        $subtasks = Subtask::all();
        $materials = Material::all(); 
       
        // Show the form to create a new material
        return view('materialcosts.create', compact('subtasks', 'materials'));
     //   return view('materialcosts.create');
    }
    public function materialAllocation(Request $request)
    {  
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
        $tasks = isAdminOrHasAllDataAccess() ? $this->workspace->tasks : $this->user->tasks;
        $subTasks = Subtask::all();
      


        $query = Material::with('UnitMeasure');
    
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->get('search', ['value' => '']);
        $order = $request->get('order', [['column' => 0, 'dir' => 'asc']]);
    
        $totalRecords = $query->count();
    
        $materials = $query
            ->skip($start)
            ->take($length)
            ->orderBy('id', 'desc')
            ->get();
            $contracts = $materials->count();
        $data = $materials->map(function ($material) {
            return [
                'id' => $material->id,
                'item' => $material->name,
                'unit' => $material->UnitMeasure->name,
                'quantity' => $material->quantity,
                'rate_with_vat' => $material->rate_with_vat,
                'amount' => $material->quantity * $material->rate_with_vat,
            ];
        });
        return view('materialcosts.materialAllocation', ['contracts' => $contracts,'materials' => $materials,'projects' => $projects,'tasks' => $tasks,'subTasks' => $subTasks
        ]);

       
        //return view('contracts.list', ['contracts' => $contracts, 'users' => $materials, 'clients' => $materials, 'projects' => $materials, 'contract_types' => $materials]);
       // return view('materialcosts.materialAllocation', compact('contracts'));
    }
    public function storeMaterialAllocation(Request $request)
    {
        $selectedMaterials = $request->input('selected_materials', []);
    
        try {
            foreach ($selectedMaterials as $material) {
                $materialCost = new MaterialCost();
                $materialCost->material_id = $material['id'];
                $materialCost->qty = $material['quantity'];
                $materialCost->amount = $material['quantity'] * $material['rate_with_vat'];
                $materialCost->subtask_id = 1;
                $materialCost->rate_with_vat = $material['rate_with_vat'];
                $materialCost->save();
            }
    
            // Set a success flash message
            session()->flash('success', 'Material allocated successfully.');
    
            // Redirect to a valid route in your application
            return redirect()->route('material-selection')->with('success', 'Material allocated successfully.');
        } catch (\Exception $e) {
            // Set an error flash message
            session()->flash('error', 'Error occurred while allocating material.');
    
            // Redirect to a valid route in your application
            return redirect()->route('material-selection')->with('error', 'Error occurred while allocating material.');
        }
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