<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Activity;

class ActivityTable extends Component
{
    public $taskId;
    public $activities;

    public function mount($taskId)
    {
        $this->taskId = $taskId;
        $this->loadActivities();
    }

    public function loadActivities()
    {
        // Adjust the query to fetch activities based on the task ID
        $this->activities = Activity::where('task_id', $this->taskId)->get();
    }

    // Add methods for creating, editing, deleting activities if needed

    public function render()
    {
        return view('livewire.activity-table', [
            'activities' => $this->activities,
        ]);
    }
}