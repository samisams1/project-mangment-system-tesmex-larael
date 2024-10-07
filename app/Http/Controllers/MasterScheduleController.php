<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Project;
use App\Models\MasterSchedule;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Str;
class MasterScheduleController extends Controller
{
    public function index()
    {
        $projects = MasterSchedule::all();
        
        return view('master-schedule.index', compact('projects'));
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