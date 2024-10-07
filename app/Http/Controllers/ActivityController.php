<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Subtask;
use App\Models\User;
use App\Models\Client;
use App\Models\Status;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Workspace;
use App\Models\Activity;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;


use App\Models\MasterSchedule;
class ActivityController extends Controller
{
    protected $workspace;
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
    public function show($id)
    {
        $tasks = Task::all();
        $task = Task::findOrFail($id);
    
        $subtasks1 = Task::select('id', 'title')
            ->with(['subtasks' => function ($query) {
                $query->select('start_date', 'end_date', 'name', 'progress', 'task_id', 'status');
            }])
            ->get();
    
        $subtasks = Subtask::with('materialCosts', 'equipmentCosts', 'laborCosts')
            ->where('task_id', $id)
            ->get();
    
        $totalCompleted = $subtasks->where('status', 'completed')->count();
        $totalPending = $subtasks->where('status', 'pending')->count();
        $totalnotstarted = $subtasks->where('status', 'not start')->count();
        $totalCancelled = $subtasks->where('status', 'cancel')->count();
    
        $data = $subtasks->map(function ($subtask) {
            $materialCosts = $subtask->materialCosts ?? collect([]); // Handle potential null materialCosts relationship
            $materialCostsData = $materialCosts->map(function ($materialCost) {
                return [
                    'name' => $materialCost->name,
                    'unit' => $materialCost->unit,
                    'planQty' => $materialCost->qty,
                    'ActualQty' => $materialCost->actualQty,
                    'plancost' => $materialCost->amount,
                    'Actualcost' => $materialCost->actualCost,
                ];
            });
    
            return [
                'id' => $subtask->id,
                'task_name' => $subtask->name,
                'status' => $subtask->status,
                'priority' => $subtask->priority,
                'planned' => $subtask->planned,
                'actual' => $subtask->actual,
                'start_date' => $subtask->start_date,
                'lead_time' => $subtask->lead_time,
                'estimated_date' => $subtask->estimated_date,
                'end_date' => $subtask->end_date,
                'progress' => $subtask->progress,
                'materialCosts' => $materialCostsData,
                'total_material_amount' => $subtask->materialCosts->sum('amount'),
                'total_equipment_amount' => $subtask->equipmentCosts->sum('amount'),
                'total_labor_amount' => $subtask->laborCosts->sum('amount'),
            ];
        });
    
        return view('activity.activity_information', [
            'task' => $task,
            'tasks' => $tasks,
            'subtasks1' => $subtasks1,
            'data' => $data,
            'totalCompleted' => $totalCompleted,
            'totalPending' => $totalPending,
            'totalnotstarted' => $totalnotstarted,
            'totalCancelled' => $totalCancelled,
        ]);
    }
    public function list(Request $request, $id = '', $type = '')
    {
        $search = request('search');
        $sort = request('sort', 'id');
        $order = request('order', 'DESC');
        $status = request('status', '');
        $user_id = request('user_id', '');
        $client_id = request('client_id', '');
        $start_date_from = request('project_start_date_from', '');
        $start_date_to = request('project_start_date_to', '');
        $end_date_from = request('project_end_date_from', '');
        $end_date_to = request('project_end_date_to', '');
        $is_favorites = request('is_favorites', '');
        
        $where = [];
        if ($status != '') {
            $where['status_id'] = $status;
        }
        
        if ($is_favorites) {
            $where['is_favorite'] = 1;
        }
    
        $activities = Activity::query();
    
        if ($id) {
            // Handle different types of relationships
            $id = explode('_', $id);
            $belongs_to = $id[0];
            $belongs_to_id = $id[1];
    
            if ($belongs_to == 'user') {
                $belongs_to = User::find($belongs_to_id);
                $activities->where('user_id', $belongs_to_id); // Assuming a relationship
            }
    
            if ($belongs_to == 'client') {
                $belongs_to = Client::find($belongs_to_id);
                $activities->where('client_id', $belongs_to_id); // Assuming a relationship
            }
        } else {
            // General query if no specific ID is provided
            $activities = isAdminOrHasAllDataAccess() ? Activity::query() : $this->user->activities();
        }
    
        if ($user_id) {
            $activities->where('user_id', $user_id);
        }
    
        if ($client_id) {
            $activities->where('client_id', $client_id);
        }
    
        if ($start_date_from && $start_date_to) {
            $activities->whereBetween('activity_start', [$start_date_from, $start_date_to]);
        }
    
        if ($end_date_from && $end_date_to) {
            $activities->whereBetween('activity_end', [$end_date_from, $end_date_to]);
        }
    
        $activities->when($search, function ($query) use ($search) {
            return $query->where('activity_name', 'like', '%' . $search . '%');
        });
    
        $activities->where($where);
        $totalActivities = $activities->count();
    
        $activities = $activities->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(function ($activity) {
                return [
                    'id' => $activity->id,
                    'project_name' => $activity->task->project->title??"N/A", // Assuming relationship
                    'task_name' => $activity->task->title, // Assuming relationship
                    'activity_name' => $activity->activity_name,
                    'activity_start' => format_date($activity->start_date),
                    'activity_end' => format_date($activity->end_date),
                    'status_id' => "<span class='badge bg-label-{}'>{$activity->status}</span>",
                    'priority_id' => $activity->priority ? "<span class='badge bg-label-'>{$activity->priority}</span>" : "<span class='badge bg-label-secondary'>No Priority</span>",
                    'created_at' => format_date($activity->created_at, true),
                    'updated_at' => format_date($activity->updated_at, true),
                ];
            });
    
        return response()->json([
            "rows" => $activities->items(),
            "total" => $totalActivities,
        ]);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'task_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'status_id' => 'required|integer', 
            'priority' => 'required|integer',  
            'start_date' => 'nullable|date', 
            'ends_at' => 'required|date|date_format:Y-m-d|after_or_equal:start_date',
        ]);  
    
        // Begin a transaction
        DB::beginTransaction();
    
        try {
            // Create the Activity
            $activity = Activity::create([  
                'task_id' => $request->task_id,  
                'name' => $request->name,  
                'status' => $request->status_id,
                'progress' => 0,  
                'priority' => $request->priority,    
                'start_date' => $request->start_date,  
                'end_date' => $request->ends_at,  
            ]);  
    
            // Create a corresponding MasterSchedule entry
            MasterSchedule::create([
                'id' => $activity->id, // Use activity ID or generate a unique ID
                'text' => $request->name,  
                'start_date' => $request->start_date, 
                'duration' => 30, // Set duration as required
                'progress' => 0, // Initially set progress to 0
                'type' => 'activity', // Set type as 'activity'
                'parent_id' => $request->task_id, // Link to the parent task
            ]);
    
            // Commit the transaction
            DB::commit();
    
            // Flash message for success
            Session::flash('message', 'Activity created successfully!');
            
            // Redirect back to the previous page
            return redirect()->back();
    
        } catch (\Exception $e) {
            // Rollback the transaction if anything fails
            DB::rollBack();
    
            // Flash an error message
            Session::flash('error', 'An error occurred while creating the activity: ' . $e->getMessage());
            
            // Redirect back with an error message
            return redirect()->back()->withInput();
        }
    }
}
