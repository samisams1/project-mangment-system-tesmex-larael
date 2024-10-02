<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Str;
class MasterScheduleController extends Controller
{
    public function index()
    {
        // Fetch all projects with their tasks and activities
        $projects = Project::with(['tasks.activities'])->get();
    
        // Prepare tasks array for Gantt chart
        $tasks = [];
        $uniqueIdCounter = 1; // Initialize a counter for unique IDs
    
        foreach ($projects as $project) {
            // Add the project as a task
            $tasks[] = [
                'id' => $uniqueIdCounter++, // Use the unique ID counter
                'text' => $project->title, // Project title
                'start_date' => $project->start_date ? $project->start_date->format('Y-m-d') : null,
                'duration' => $project->duration ?? 0,
                'progress' => $project->progress ?? 0,
                'type' => 'project',
            ];
    
            foreach ($project->tasks as $task) {
                // Add the task under the project
                $tasks[] = [
                    'id' => $uniqueIdCounter++, // Use the unique ID counter
                    'text' => $task->title, // Task title
                    'start_date' => $task->start_date ? $task->start_date->format('Y-m-d') : null,
                    'duration' => $task->duration ?? 0,
                    'progress' => $task->progress ?? 0,
                    'parent' => $project->id, // Link task to its parent project
                ];
    
                foreach ($task->activities as $activity) {
                    // Add the activity under the task
                    $tasks[] = [
                        'id' => $uniqueIdCounter++, // Use the unique ID counter
                        'text' => $activity->name, // Activity name
                        'start_date' => $activity->start_date ? $activity->start_date->format('Y-m-d') : null,
                        'duration' => $activity->duration ?? 0,
                        'progress' => $activity->progress ?? 0,
                        'parent' => $task->id, // Link activity to its parent task
                    ];
                }
            }
        }
    
        \Log::info($tasks); // Log the tasks for debugging
        // Return the view with the fetched tasks
        return view('master-schedule.index', compact('tasks','projects'));
    }

    public function data(Request $request)
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
    }
}