<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Project;
use App\Models\MasterSchedule;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Status; // Importing the Status model
use App\Models\Priority; // Importing the Priority model
use App\Models\Activity;
use App\Models\Workspace;
use App\Models\User;
use App\Models\Site;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MasterScheduleController extends Controller
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
   
    public function index(Request $request)
    {

        $toSelectProjectUsers = $this->workspace->users;
        $toSelectProjectClients = $this->workspace->clients;
        $sites = Site::all();
        //dd($sites);
           // Fetch filters from the request (if necessary)
    $statusFilter = $request->input('status');
    $priorityFilter = $request->input('priority');

    // Query the projects with optional filters and eager loading
    $projectsQuery = Project::with(['tasks.activities', 'site']);

    // Apply status filter
    if ($statusFilter) {
        $projectsQuery->where('status_id', $statusFilter);
    }

    // Apply priority filter
    if ($priorityFilter) {
        $projectsQuery->where('priority_id', $priorityFilter);
    }

    // Paginate the results (10 items per page)
    $projectsData = $projectsQuery->paginate(10);

        $projectsData = Project::with(['tasks.activities', 'site'])->get()->map(function ($project) {
            // Fetch the status and priority models based on the project's status_id and priority_id
            $status = Status::find($project->status_id); // Get the status for the current project
            $priority = Priority::find($project->priority_id); // Get the priority for the current project
            $creator = User::find($project->created_by); 
            // Calculate duration
            $startDate = \Carbon\Carbon::parse($project->start_date);
            $endDate = \Carbon\Carbon::parse($project->end_date);
            $now = \Carbon\Carbon::now();
    
            // Duration calculation
            $duration = $startDate->diff($endDate);
            $durationParts = [];
            if ($duration->y > 0) {
                $durationParts[] = "{$duration->y} Y" . ($duration->y > 1 ? 's' : '');
            }
            if ($duration->m > 0) {
                $durationParts[] = "{$duration->m} M" . ($duration->m > 1 ? 's' : '');
            }
            if ($duration->d > 0) {
                $durationParts[] = "{$duration->d} D" . ($duration->d > 1 ? 's' : '');
            }
            $durationFormatted = implode(', ', $durationParts) ?: '0 D'; // Fallback if all are 0
    
            // Remaining calculation
            $remaining = $now->diff($endDate);
            if ($now->greaterThan($endDate)) {
                // If the current date is past the end date, calculate how long ago it passed
                $passedDuration = $endDate->diff($now);
                $passedParts = [];
                if ($passedDuration->y > 0) {
                    $passedParts[] = "{$passedDuration->y} Y" . ($passedDuration->y > 1 ? 's' : '');
                }
                if ($passedDuration->m > 0) {
                    $passedParts[] = "{$passedDuration->m} M" . ($passedDuration->m > 1 ? 's' : '');
                }
                if ($passedDuration->d > 0) {
                    $passedParts[] = "{$passedDuration->d} D" . ($passedDuration->d > 1 ? 's' : '');
                }
                $remainingFormatted = "Pass: " . implode(', ', $passedParts) ?: '0 D'; // Fallback if all are 0
            } else {
                // Format remaining time
                $remainingParts = [];
                if ($remaining->y > 0) {
                    $remainingParts[] = "{$remaining->y} Y" . ($remaining->y > 1 ? 's' : '');
                }
                if ($remaining->m > 0) {
                    $remainingParts[] = "{$remaining->m} M" . ($remaining->m > 1 ? 's' : '');
                }
                if ($remaining->d > 0) {
                    $remainingParts[] = "{$remaining->d} D" . ($remaining->d > 1 ? 's' : '');
                }
                // If no remaining time, show "Due Today" if the end date is today
                if ($remaining->days === 0) {
                    $remainingFormatted = "Due Today";
                } else {
                    $remainingFormatted = implode(', ', $remainingParts) ?: '0 D'; // Fallback if all are 0
                }
            }
    
            // Determine color based on remaining days
            $colorClass = '';
            if ($now->greaterThan($endDate)) {
                $colorClass = 'text-danger'; // Overdue
            } elseif ($remaining->days <= 7) {
                $colorClass = 'text-warning'; // Due soon
            } else {
                $colorClass = 'text-success'; // Active
            }
    
            // Build status options HTML
            $statusOptions = '';
            if ($status) { // Check if the status exists
                $statusOptions .= "<option value='{$status->id}' class='badge bg-label-{$status->color}' selected>{$status->title}</option>";
            }
    
            // Build priority options HTML
            $priorityOptions = '';
            if ($priority) { // Check if the priority exists
                $priorityOptions .= "<option value='{$priority->id}' class='badge bg-label-{$priority->color}' selected>{$priority->title}</option>";
            }
            $created_by = '';
            if ($creator) { //creator
                $created_by .= "<p>{$creator->first_name}</p>";
            }
            return [
                "id" => $project->id,
                "wbs" => $project->id, // Assuming this is intended
                "title" => $project->title,
                "site" => $project->site ? $project->site->name : null, // Adjust field as necessary
                "priority" => $priorityOptions, // Return the HTML for priority options
                "startDate" => $startDate->format('d-m-Y'), // Format start date
                "endDate" => $endDate->format('d-m-Y'), // Format end date
                "duration" => $durationFormatted, // Total duration formatted as a string
                "remaining" => $remainingFormatted, // Remaining time formatted as a string
                "remainingColor" => $colorClass, // Color class for remaining
                "status" => $statusOptions, // Return the HTML for status options
                "assignedTo" => $project->assigned_to,
                "createdBy" => $created_by, // Fetch creator's name from the user table
                "createdDate" => $project->created_at->toDateString(),
                "tasks" => $project->tasks->map(function ($task) {
                    return [
                        "id" => $task->id,
                        "wbs" => $task->project_id,
                        "title" => $task->title,
                        "site" => $task->site, // Assuming this is intended
                        "priority" => $task->priority_id,
                        "startDate" => \Carbon\Carbon::parse($task->start_date)->format('d-m-Y'), // Format task start date
                        "endDate" => \Carbon\Carbon::parse($task->end_date)->format('d-m-Y'), // Format task end date
                        "status" => $task->status_id, // Assuming status_id is what you want
                        "assignedTo" => $task->assigned_to,
                        "createdBy" => $task->created_by,
                        "createdDate" => $task->created_at->toDateString(),
                        "activities" => $task->activities->map(function ($activity) {
                            return [
                                "id" => $activity->id,
                                "status" => $activity->status_id, // Ensure this is accessible
                                "name" => $activity->name, // Ensure this is accessible
                            ];
                        })->toArray(), // Ensure it returns an array of activities
                    ];
                })->toArray(),
            ];
        });
    
        // Fetch other necessary data
        $projects = MasterSchedule::all();
        $priority = Priority::all(); // Assuming you need this for some purpose
        $activities = 2; // Adjust as necessary
        $id = 1; // Adjust as necessary
        $users = $projects; // Consider renaming for clarity
        $clients = $projects; // Consider renaming for clarity
        $sites = $sites;
        // Pass the data to the view
        return view('master-schedule.index', compact('projects','sites','toSelectProjectClients','toSelectProjectUsers', 'activities', 'id', 'users', 'clients', 'priority', 'projectsData'));
    }
    
   /* public function index()
    {
        $projects = MasterSchedule::all();
        $project = Project::all();
        $task = Task::all();
        $priority = Priority::all();
        $activities = 2;
        $id =1;
        $users = $projects;
        $clients = $projects;
        $priority = $priority;
        $project = $project;
        $task = $task;
        
        return view('master-schedule.index', compact('projects','activities','id','users','clients','priority','project','task'));
    }*/
    public function data(Request $request) {
        $activities = Activity::with('task.project');
    
        // Input sanitization and defaults
        $search = $request->input('search', '');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'DESC');
        $status = $request->input('status', '');
        $user_id = $request->input('user_id', '');
        $client_id = $request->input('client_id', '');
        $start_date_from = $request->input('activity_start_date_from', '');
        $start_date_to = $request->input('activity_start_date_to', '');
        $end_date_from = $request->input('activity_end_date_from', '');
        $end_date_to = $request->input('activity_end_date_to', '');
        $is_favorites = $request->input('is_favorites', '');
    
        // Build where clauses
        $where = [];
        if ($status) {
            $where['status_id'] = $status;
        }
    
        if ($is_favorites) {
            $where['is_favorite'] = 1;
        }
    
        // Date filtering
        if ($start_date_from && $start_date_to) {
            $activities->whereBetween('start_date', [$start_date_from, $start_date_to]);
        }
        if ($end_date_from && $end_date_to) {
            $activities->whereBetween('end_date', [$end_date_from, $end_date_to]);
        }
    
        // Search filtering
        if ($search) {
            $activities->where('name', 'like', '%' . $search . '%');
        }
    
        // Apply other filters
        $activities->where($where);
        $totalActivities = $activities->count();
    
        // Fetch statuses and priorities
        $statuses = Status::all();
        $priorities = Priority::all();
    
        // Set default limit to 10 if not provided
        $limit = $request->input("limit", 10);
    
        // Paginate and format response
        $activities = $activities->orderBy($sort, $order)
            ->paginate($limit)
            ->through(function ($activity) use ($statuses, $priorities) {
                return $this->formatActivity($activity, $statuses, $priorities);
            });
    
        return response()->json([
            "rows" => $activities->items(),
            "total" => $totalActivities,
            "current_page" => $activities->currentPage(),
            "last_page" => $activities->lastPage(),
            "per_page" => $activities->perPage(),
        ]);
    }

    private function formatActivity($activity, $statuses, $priorities) {
        // Ensure status and priority are loaded
    $status = $activity->status; // This should be a Status model instance
    $priority = $activity->priority; // This should be a Priority model instance
    $statusOptions = '';
    foreach ($statuses as $status) {
        $selected = $activity->status == $status->id ? 'selected' : '';
        $statusOptions .= "<option value='{$status->id}' class='badge bg-label-$status->color' $selected>$status->title</option>";
    }
    $priorityOptions = "";
    foreach ($priorities as $priority) {
        $selected = $activity->priority == $priority->id ? 'selected' : '';
        $priorityOptions .= "<option value='{$priority->id}' class='badge bg-label-$priority->color' $selected>$priority->title</option>";
    }
        return [
            'id' => $activity->id,
            'activiity' => $activity->name,
            'progress' => $activity->progress,
            'project'=>$activity->task->project->title,
            'task'=>$activity->task->title,
           // 'priority_id' => $priorityBadge,
            'start_date' => format_date($activity->start_date),
            'end_date' => format_date($activity->end_date),
            'status' =>  $statusBadge = $status ? "<span class='badge bg-label-{$status->color}'>{$status->title}</span>" : "<span class='badge bg-label-secondary'>No Status</span>",
            'priority' =>  $priorityBadge = $priority ? "<span class='badge bg-label-{$priority->color}'>{$priority->title}</span>" : "<span class='badge bg-label-secondary'>No Priority</span>",
            'assigned_to' => $activity->assigned_to,
            'progress' => $activity->progress,
            'created_at' => format_date($activity->created_at, true),
            'updated_at' => format_date($activity->updated_at, true),
        ];
    }
    public function store(Request $request)
    {
        try {
            // Create a new MasterSchedule instance
            $task = new MasterSchedule();
            
            // Set the parent ID from the request
            $task->parent = $request->parent;
    
            // Find the parent task from MasterSchedule by the parent ID
            $parentTask = MasterSchedule::find($request->parent);
    
            // Check if the parent task exists
            if ($parentTask) {
                // Set the type based on the parentTask type
                if ($parentTask->type === 'project') {
                    $task->type = 'task';
                    $newTask = new Task();
                    $newTask->text = $request->text;
                    $newTask->start_date = $request->start_date;
                    $newTask->duration = $request->duration;
                    $newTask->progress = 0;
                    $newTask->project_id = 1;
                    $newTask->due_date  =$request->start_date;
                    $newTask->title=$request->text;
                    $newTask->description = '1';
                    $newTask->status_id = 0;
                    $newTask->priority_id = 14;
                    $newTask->workspace_id = 1;
                    $newTask->created_by=1;
                    // Save the new Task
                    $newTask->save();
              
                } elseif ($parentTask->type === 'task') {
                    $task->type = 'activity';
                    // Insert into Task model
                         // Insert into Task model
                         $newTask = new Task();
                         $newTask->text = $request->text;
                         $newTask->start_date = $request->start_date;
                         $newTask->duration = $request->duration;
                         $newTask->progress = 0;
                         $newTask->workspace_id = 1;
                         $newTask->project_id = 1;
                         $newTask->due_date  =$request->start_date;
                         $newTask->title=$request->text;
                         $newTask->description = '1';
                         $newTask->status_id = 0;
                         $newTask->priority_id = 14;
                         $newTask->created_by=1;
                         // Save the new Task
                         $newTask->save();
                          // Log the creation of the new task
                Log::info('New Task created:', [
                    'id' => $newTask->id,
                    'title' => $newTask->title,
                    'project_id' => $newTask->project_id
                ]);
                } else {
                    $task->type = 'new'; // Default type if parentTask type is neither 'project' nor 'task'
                }
            } else {
                // Handle the case where the parent task does not exist
                return redirect()->back()->with('error', 'Parent task not found.');
            }
    
            // Set other task properties
            $task->text = $request->text;
            $task->start_date = $request->start_date;
            $task->duration = $request->duration;
            $task->progress = $request->has("progress") ? $request->progress : 0;
    
            // Save the new task
            $task->save();
            
            // Set flash message for success
            Session::flash('message', 'Schedule created successfully.');
    
            // Redirect back or to a specific route
            return redirect()->route('master-schedule.index'); // Replace with your actual route
        } catch (\Exception $e) {
            // Handle any exceptions that may occur
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
   /* public function store(Request $request)
{
    // Start a database transaction
    DB::beginTransaction();

    try {
        // Create a new MasterSchedule instance
        $task = new MasterSchedule();
        
        // Set the parent ID from the request
        $task->parent = $request->parent;

        // Find the parent task by ID
        $parentTask = MasterSchedule::find($request->parent);

        // Check if the parent task exists
        if ($parentTask) {
            // Set the type based on the parentTask type
            if ($parentTask->type === 'project') {
                $task->type = 'task';

                // Insert into Task model
                $newTask = new Task();
                $newTask->text = $request->text;
                $newTask->start_date = $request->start_date;
                $newTask->duration = $request->duration;
                $newTask->progress = $request->has("progress") ? $request->progress : 0;
                $newTask->project_id = 1;
                $newTask->due_date  = 1;
                $newTask->$request->text;;
                $newTask->description = 1;
                $newTask->status_id = 1;
                $newTask->priority_id = 1;
                // Save the new Task
                $newTask->save();
            } elseif ($parentTask->type === 'task') {
                $task->type = 'activity';

                // Insert into Activity model
                $newActivity = new Activity();
                $newActivity->text = $request->text;
                $newActivity->start_date = $request->start_date;
                $newActivity->duration = $request->duration;
                $newActivity->progress = $request->has("progress") ? $request->progress : 0;

                // Save the new Activity
                $newActivity->save();
            } else {
                // Handle other types if necessary
                $task->type = 'new';
            }
        } else {
            // Handle the case where the parent task does not exist
            return response()->json([
                "action" => "error",
                "message" => "Parent task not found."
            ], 404);
        }

        // Save the MasterSchedule task
        $task->text = $request->text; // Additional fields if needed
        $task->save();

        // Commit the transaction
        DB::commit();

        // Set flash message for success
        Session::flash('message', 'Schedule created successfully.');

        // Redirect or return a response
        return response()->json([
            "action" => "inserted",
            "tid" => $task->id
        ]);

    } catch (\Exception $e) {
        // Rollback the transaction on error
        DB::rollBack();

        // Handle the exception
        return response()->json([
            "action" => "error",
            "message" => "Error occurred: " . $e->getMessage()
        ], 500);
    }
}*/
   /* public function data(Request $request)
    {
        $tasks = Task::with('activity')->get();
        $data = [  
            [  
                "id" => 1,  
                "text" => "Project 1",  
                "start_date" => "2023-10-01",  
                "duration" => 30,  
                "progress" => 0.4,  
                "children" => [  
                    [  
                        "id" => 2,  
                        "text" => "Task 1.1",  
                        "start_date" => "2023-10-01",  
                        "duration" => 15,  
                        "progress" => 0.6,  
                    ],  
                    [  
                        "id" => 3,  
                        "text" => "Task 1.2",  
                        "start_date" => "2023-10-10",  
                        "duration" => 15,  
                        "progress" => 0.2,  
                    ],  
                ],  
            ],  
            // More projects, tasks...  
        ];  
    
        return response()->json($data);
    }*/
}