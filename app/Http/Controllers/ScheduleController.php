<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Activity;

class ScheduleController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['activities:id,task_id,name']) // Select specific fields
        ->select('id', 'start_date', 'due_date', 'title') // Include 'id' for the relationship
        ->get();

/*return response()->json([
    'total' => $tasks,
]);*/
        return view('schedule.index', [
            'tasks' => $tasks
        ]);
    }
}