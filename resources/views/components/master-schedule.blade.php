<div class="tab-pane fade active show" id="navs-top-schedule" role="tabpanel">
    <!-- Filter Section -->
    <div class="mb-4">
    <form method="GET" action="{{ route('master-schedule.index') }}" class="row g-3 align-items-end">
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Select Status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>{{ $status->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="priority" class="form-select">
                <option value="">Select Priority</option>
                @foreach($priorities as $priority)
                    <option value="{{ $priority->id }}" {{ request('priority') == $priority->id ? 'selected' : '' }}>{{ $priority->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>
</div>
    <!-- Action Buttons -->
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <input type="text" id="searchInput" class="form-control d-inline-block ms-2" placeholder="Search..." onkeyup="filterTable()" style="width: 200px;">
        <div>
            <form method="GET" action="{{ route('master-schedule.export') }}" class="d-inline">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="priority" value="{{ request('priority') }}">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <button type="submit" class="btn btn-info me-2"><i class="fas fa-file-pdf"></i> PDF</button>
            </form>
            <form method="GET" action="{{ route('master-schedule.exportCsv') }}" class="d-inline">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="priority" value="{{ request('priority') }}">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <button type="submit" class="btn btn-success me-2"><i class="fas fa-file-csv"></i> CSV</button>
            </form>
            <button class="btn btn-warning" onclick="printTable()"><i class="fas fa-print"></i> Print</button>
        </div>
    </div>

    <h3>Available Projects</h3>
    <!-- Projects Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover" id="projectsTable">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>WBS</th>
                    <th>Projects</th>
                    <th>Site</th>
                    <th>Priority</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projectsData as $project)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="me-2">{{ $project['id'] ?? 'N/A' }}</span> 
                                <button class="btn btn-circle toggle-tasks badge bg-success" data-id="{{ $project['id'] }}" onclick="toggleTasks({{ $project['id'] }}, this)">+</button>
                            </div>
                        </td>
                        <td>{{ $project['wbs'] ?? 'N/A' }}</td>
                        <td>{{ Str::limit(trim($project['title'] ?? 'N/A'), 10) }}</td>
                        <td>{{ $project['site'] ?? 'N/A' }}</td>
                        <td>{!! $project['priority'] ?? 'N/A' !!}</td>
                        <td class="date-cell">{{ \Carbon\Carbon::parse($project['startDate'] ?? '')->format('d-m-Y') }}</td>
                        <td class="date-cell">{{ \Carbon\Carbon::parse($project['endDate'] ?? '')->format('d-m-Y') }}</td>
                        <td class="text-center">{{ $project['duration'] ?? 'N/A' }}</td>
                        <td>{!! $project['status'] ?? 'N/A' !!}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $project['id'] }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $project['id'] }}">
                                    <li><a class="dropdown-item" href=""><i class="fas fa-eye"></i> View</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="editProject({{ $project['id'] }})"><i class="fas fa-pencil-alt"></i> Edit</a></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteProject({{ $project['id'] }})"><i class="fas fa-trash"></i> Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr class="tasks-row" id="tasks-{{ $project['id'] }}" style="display: none;">
                        <td colspan="12">
                        <div class="mb-3 d-flex justify-content-between align-items-center p-3 bg-light rounded shadow-sm">
                        <h5 class="mb-0 text-success">Project : {{ $project['title'] }}</h5>
                        <p class="text-muted mb-1">The following are the list of tasks for this project:</p>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#create_task_modal"  data-project-id="{{$project['id']  }}">
        Create Task  
    </button>

</div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>WBS</th>
                                        <th>Task</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Duration</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($project['tasks']) && count($project['tasks']) > 0)
                                        @foreach($project['tasks'] as $task)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2">{{ $task['id'] ?? 'N/A' }}</span>
                                                        <button class="btn btn-circle toggle-activities badge bg-primary" data-id="{{ $task['id'] }}" onclick="toggleActivities({{ $task['id'] }}, this)">+</button>
                                                    </div>
                                                </td>
                                                <td>{{ $task['wbs'] ?? 'N/A' }}</td>
                                                <td>{{ Str::limit(trim($task['title'] ?? 'N/A'), 10) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($task['startDate'] ?? '')->format('d-m-Y') }}</td>
                                               
                                                <td>{{ \Carbon\Carbon::parse($task['endDate'] ?? '')->format('d-m-Y') }}</td>
                                                <td>{{ $task['duration'] ?? 'N/A' }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="taskDropdownMenuButton{{ $task['id'] }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Actions
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="taskDropdownMenuButton{{ $task['id'] }}">
                                                            <li><a class="dropdown-item" href=""><i class="fas fa-eye"></i> View</a></li>
                                                            <li><a class="dropdown-item" href="#" onclick="editTask({{ $task['id'] }})"><i class="fas fa-pencil-alt"></i> Edit</a></li>
                                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteTask({{ $task['id'] }})"><i class="fas fa-trash"></i> Delete</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="activities-row" id="activities-{{ $task['id'] }}" style="display: none;">
                                                <td colspan="12">
                                                <x-activity-table :activities="$task['activities']" :taskId="$task['id']"  :projectId="$project['id']" :taskName="$task['title']" />
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="12" class="text-center">No tasks available for this project.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        {{ $projectsData->appends(request()->query())->links() }}
    </div>
</div>

<x-tasks.task :project_id="$project['id']" : :users="$users" />

<style>
    /* Additional styling for better table aesthetics */
    #projectsTable {
        margin: 0;
        border-collapse: collapse;
    }
    #projectsTable th, #projectsTable td {
        vertical-align: middle;
        padding: 12px;
        font-size: 14px;
        white-space: nowrap;
    }
    #projectsTable th {
        background-color: #f8f9fa;
        text-align: center;
    }
    #projectsTable tr:hover {
        background-color: #e9ecef;
    }
    .btn-circle {
        width: 30px;
        height: 30px;
        border-radius: 15px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    <style>
    .table-header-custom {
        background-color: #1B8596; /* Set the desired background color */
        color: white; /* Optional: Set text color for better contrast */
    }
    #projectsTable th {
    background-color: #1B8596;
    text-align: center;
    color: white;
}
</style>
</style>

<script>
    function toggleTasks(projectId, button) {
        const tasksRow = document.getElementById(`tasks-${projectId}`);
        tasksRow.style.display = tasksRow.style.display === "none" ? "table-row" : "none";
        button.innerHTML = tasksRow.style.display === "none" ? '+' : '-';
    }

    function toggleActivities(taskId, button) {
        const activitiesRow = document.getElementById(`activities-${taskId}`);
        activitiesRow.style.display = activitiesRow.style.display === "none" ? "table-row" : "none";
        button.innerHTML = activitiesRow.style.display === "none" ? '+' : '-';
    }

    function filterTable() {
        const input = document.getElementById("searchInput").value.toLowerCase();
        const rows = document.querySelectorAll("#projectsTable tbody tr");
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(input) ? "" : "none";
        });
    }

    function printTable() {
        window.print();
    }
</script>
