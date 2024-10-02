<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Task;

class GanttController extends Controller
{
    public function index()
    {
        // Fetch projects with their associated tasks and activities
        $projects = Project::with('tasks.activity')->get();
    
        // Transform the project data for the Gantt chart
        $data = $projects->map(function($project) {
            // Initialize project data
            $projectData = [
                "id" => $project->id,  // Assuming Project has an 'id' field
                "text" => "samisams",
                "start_date" => "2023-10-01",    // Format as needed
                "end_date" =>"2023-10-01",  
                "status" => $project->status,  // Assuming Project has a 'status' field
                "tasks" => [], // Initialize an array for tasks
            ];
    
            // Loop through each task in the project
            foreach ($project->tasks as $task) {
                $taskData = [
                    "id" => $task->id,
                    "text" => $task->name,
                    "start_date" => "2023-10-01", 
                    "end_date" => "2023-10-01",  
                    "progress" => $task->progress ?? 0,  // Default progress to 0 if not set
                    "activities" => [], // Initialize an array for activities
                ];
    
                // Loop through each activity in the task
                foreach ($task->activity as $activity) {
                    $taskData['activities'][] = [
                        "id" => $activity->id,
                        "text" => $activity->name,
                        "start_date" => "2023-10-01",  
                        "end_date" => "2023-10-01",  
                        "progress" => $activity->progress ?? 0,
                        "parent" => $task->id, // Link activity to its parent task
                    ];
                }
    
                // Add task data to project data
                $projectData['tasks'][] = $taskData;
            }
    
            return $projectData; // Return the formatted project data
        });
    
        // Pass the formatted data to the view
        return view('gantt.index', compact('data'));
    }
    /*public function data(Request $request)
    {
        $tasks = Task::with('activity')->get();
        $data = [
            "data" => [],
            "links" => []
        ];
        
    
        foreach ($tasks as $task) {
            $taskData = [
                "id" => $task->id,
                "text" => $task->title,
                "start_date" => Carbon::parse($task->start_date)->toISOString(),
                "end_date" => Carbon::parse($task->due_date)->toISOString(),
                "progress" => 0 // You can calculate the progress based on your application's logic
            ];
    
            $taskData["activity"] = [];
    
            // Add child tasks
            foreach ($task->activity as $child) {
                $childData = [
                    "id" => $child->id,
                    "text" => $child->name,
                    "start_date" => Carbon::parse($child->start_date)->toISOString(),
                    "end_date" => Carbon::parse($child->due_date)->toISOString(),
                    "progress" => 0, // You can calculate the progress based on your application's logic
                    "parent" => $task->id
                ];
                $taskData["activity"][] = $childData;
            }
    
            $data["data"][] = $taskData;
    
            // Add links between tasks and child activities
            foreach ($taskData["activity"] as $child) {
                $data["links"][] = [
                    "id" => $child["id"],
                    "source" => $taskData["id"],
                    "target" => $child["id"],
                    "type" => "finish-start"
                ];
            }
        }
    
        return response()->json($data);
    }*/
    public function data()  
    {  
        // Hard-coded Gantt chart data  
        $data = [  
            ['id' => 1, 'text' => 'Project 1', 'start_date' => '2023-04-01', 'end_date' => '2023-04-10', 'duration' => 10],  
            ['id' => 2, 'text' => 'Project 2', 'start_date' => '2023-04-05', 'end_date' => '2023-04-15', 'duration' => 10],  
            ['id' => 3, 'text' => 'Project 3', 'start_date' => '2023-04-12', 'end_date' => '2023-04-20', 'duration' => 9],  
        ];  
    
        return response()->json($data);  
    }

}