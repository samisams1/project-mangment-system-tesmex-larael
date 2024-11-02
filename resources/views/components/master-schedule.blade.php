<div class="tab-pane fade active show" id="navs-top-schedule" role="tabpanel">
    <!-- Filter Section -->
    <div class="mb-3">
        <form method="GET" action="{{ route('master-schedule.index') }}">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <select name="status" class="form-select">
                        <option value="">Select Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>{{ $status->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select name="priority" class="form-select">
                        <option value="">Select Priority</option>
                        @foreach($priorities as $priority)
                            <option value="{{ $priority->id }}" {{ request('priority') == $priority->id ? 'selected' : '' }}>{{ $priority->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3 mb-2">
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-1 mb-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Action Buttons -->
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#create_project_modal">
                Create Project
            </button>
            <input type="text" id="searchInput" class="form-control d-inline-block" placeholder="Search..." onkeyup="filterTable()" style="width: 150px; margin-left: 10px;">
        </div>
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

    <!-- Projects Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover" id="projectsTable">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>WBS</th>
                    <th>Title</th>
                    <th>Site</th>
                    <th>Priority</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Created By</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projectsData as $project)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="me-2">{{ $project['id'] ?? 'N/A' }}</span>
                                <button class="btn btn-circle toggle-tasks badge bg-success" data-id="{{ $project['id'] }}" onclick="toggleTasks({{ $project['id'] }})">+</button>
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
                        <td>{{ $project['assignedTo'] ?? 'N/A' }}</td>
                        <td>{!! $project['createdBy'] ?? 'N/A' !!}</td>
                    </tr>
                    <tr class="tasks-row" id="tasks-{{ $project['id'] }}" style="display: none;">
                        <td colspan="11">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>WBS</th>
                                        <th>Task</th>
                                        <th>Priority</th>
                                        <th>Start Date</th>
                                        <th>Duration</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Assigned To</th>
                                        <th>Created By</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($project['tasks']) && count($project['tasks']) > 0)
                                        @foreach($project['tasks'] as $task)
                                            <tr>
                                                <td>1</td>
                                                <td>{{ $task['wbs'] ?? 'N/A' }}</td>
                                                <td>{{ $task['title'] ?? 'N/A' }}</td>
                                                <td>{{ $task['priority'] ?? 'N/A' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($task['startDate'] ?? '')->format('d-m-Y') }}</td>
                                                <td>{{ $task['duration'] ?? 'N/A' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($task['endDate'] ?? '')->format('d-m-Y') }}</td>
                                                <td>{{ $task['status'] ?? 'N/A' }}</td>
                                                <td>{{ $task['assignedTo'] ?? 'N/A' }}</td>
                                                <td>{{ $task['createdBy'] ?? 'N/A' }}</td>
                                                <td>{{ $task['createdDate'] ?? 'N/A' }}</td>
                                                <td>
                                                    <button class="btn btn-danger" onclick="deleteTask({{ $task['id'] ?? '0' }})">Delete</button>
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
    {{ $projectsData->appends(request()->query())->links() }}
</div>

<style>
    /* Additional styling for better table aesthetics */
    #projectsTable {
        margin: 0; /* Remove margin */
        border-collapse: collapse; /* Collapse borders for cleaner look */
    }
    #projectsTable th, #projectsTable td {
        vertical-align: middle; /* Center align text vertically */
        padding: 10px; /* Adjust padding for a better look */
        font-size: 14px; /* Font size for readability */
        white-space: nowrap; /* Prevent text wrapping */
    }
    #projectsTable th {
        background-color: #f8f9fa; /* Light gray background for headers */
        text-align: center; /* Center align headers */
    }
    #projectsTable tr:hover {
        background-color: #e9ecef; /* Light gray background on hover */
    }
    .btn-circle {
        width: 30px;
        height: 30px;
        border-radius: 15px; /* Make the button circular */
        display: flex;
        justify-content: center;
        align-items: center;
    }
    /* Adjust column widths for better layout */
    #projectsTable td:nth-child(1),
    #projectsTable td:nth-child(2),
    #projectsTable td:nth-child(3),
    #projectsTable td:nth-child(4),
    #projectsTable td:nth-child(5),
    #projectsTable td:nth-child(10) {
        width: 100px; /* Set a width for specified columns */
    }
    #projectsTable td:nth-child(6),
    #projectsTable td:nth-child(7) {
        width: 120px; /* Set a width for date columns */
    }
</style>

<script>
    function toggleTasks(projectId) {
        const tasksRow = document.getElementById(`tasks-${projectId}`);
        if (tasksRow.style.display === "none") {
            tasksRow.style.display = "table-row"; // Show the tasks
        } else {
            tasksRow.style.display = "none"; // Hide the tasks
        }
    }

    function deleteTask(taskId) {
        // Implement delete task functionality
        console.log(`Delete task with ID: ${taskId}`);
    }

    function filterTable() {
        // Implement search filtering functionality
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase();
        const table = document.getElementById("projectsTable");
        const trs = table.getElementsByTagName("tr");
        
        for (let i = 1; i < trs.length; i++) {
            const tds = trs[i].getElementsByTagName("td");
            let rowVisible = false;
            for (let j = 0; j < tds.length; j++) {
                if (tds[j] && tds[j].innerText.toLowerCase().indexOf(filter) > -1) {
                    rowVisible = true;
                    break;
                }
            }
            trs[i].style.display = rowVisible ? "" : "none";
        }
    }
</script>