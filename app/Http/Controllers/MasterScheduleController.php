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
use Illuminate\Support\Str;
class MasterScheduleController extends Controller
{
    public function index()
    {
        $projects = MasterSchedule::all();
        $activities = 2;
        $id =1;
        $users = $projects;
        $clients = $projects;
        $priority = $projects;
        return view('master-schedule.index', compact('projects','activities','id','users','clients','priority'));
    }
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