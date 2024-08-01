<?php
namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the warehouses.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $warehouses = Warehouse::all();
        return view('warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new warehouse.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('warehouses.create');
    }

    /**
     * Store a newly created warehouse in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'manager' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'created_by' => 'required|string|max:255',
        ]);

        // Create a new warehouse
        $warehouse = new Warehouse();
        $warehouse->name = $validatedData['name'];
        $warehouse->description = $validatedData['description'];
        $warehouse->location = $validatedData['location'];
        $warehouse->manager = $validatedData['manager'];
        $warehouse->contact_info = $validatedData['contact_info'];
        $warehouse->created_by = $validatedData['created_by'];
        $warehouse->save();

        // Return a response or redirect to the appropriate view
        return response()->json($warehouse, 201);
    }

    /**
     * Return the warehouse data for the table.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data()
    {
        $warehouses = Warehouse::all();
        return response()->json($warehouses);
    }
}