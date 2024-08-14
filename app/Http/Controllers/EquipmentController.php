<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\UnitMeasure;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // fetch session and use it in entire class with constructor
            $this->user = getAuthenticatedUser();
            return $next($request);
        });
    }
    public function index()
    {
        $units = UnitMeasure::all();
        $warehouses = Warehouse::all();
        $equipments = Equipment::with(['createdBy', 'updatedBy'])->get();
        return view('equipments.index', compact('equipments', 'warehouses','units'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       // $units = UnitMeasure::all();
        $warehouses = Warehouse::all();
        $users = User::all();
        return view('equipments.create', compact('units', 'warehouses', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    try {
        // Validate the input data
        $validatedData = $request->validate([
            'item' => 'required',
            'quantity' => 'numeric',
            'rate_with_vat' => 'numeric',
            'amount' => 'numeric',
            'remark' => 'nullable',
            'status' => 'required',
            'type' => 'required',
            'reorder_quantity' => 'numeric',
            'min_quantity' => 'numeric',
            'unit_id' => 'exists:unit_measures,id',
            'warehouse_id' => 'exists:warehouses,id',
        ]);

        // Set the createdBy and updatedBy fields
        $validatedData['created_by'] = $this->user->id;
        $validatedData['updated_by'] = $this->user->id;

        // Create the new equipment
        $equipment = Equipment::create($validatedData);

        // Return a success message as a flash message
        return redirect()->back()->with('success', 'Equipment created successfully.');
    } catch (\Exception $e) {
        // Return an error message as a flash message
        return redirect()->back()->with('error', 'Error creating equipment: ' . $e->getMessage());
    }
}
public function data()
{
    $warehouses = Equipment::all();
    return response()->json($warehouses);
}
    // Add more methods for update, show, destroy, etc.
}