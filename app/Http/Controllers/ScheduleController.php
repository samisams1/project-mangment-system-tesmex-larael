<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class ScheduleController extends Controller
{
    public function index()
    {
        $tasks = Task::select('start_date', 'due_date', 'title')->get();
//return $tasks;
        return view('schedule.index', [
            'tasks' => $tasks
        ]);
    }
}