@extends('layout') <!-- Adjust according to your layout -->

@section('content')
<div class="container">
    <h1>Master Schedule</h1>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-schedule" aria-controls="navs-top-schedule" aria-selected="true">
                <i class="menu-icon tf-icons bx bx-wrench text-warning"></i> Schedule
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-gantt" aria-controls="navs-top-gantt" aria-selected="false">
                <i class="menu-icon tf-icons bx bx-paper-plane text-success"></i> Gantt Chart
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3">
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

            <!-- Export and Print Buttons -->
            <div class="mb-3 d-flex justify-content-end">

            <div class="col-md-3 mb-2">
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#create_project_modal">
                    Create Project
                </button>
                </div>
                <div class="col-md-3 mb-2">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search..." onkeyup="filterTable()" style="width: 150px; display: inline-block;">
                </div>
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

            <!-- Projects Table -->
            <div class="table-responsive">
                <table class="table table-bordered" id="projectsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>WBS</th>
                            <th>Title</th>
                            <th>Site</th>
                            <th>Priority</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Duration</th>
                            <th>Remaining</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                            <th>Created By</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($projectsData as $project)
    <tr>
        <td>{{ $project['id'] ?? 'N/A' }}</td>
        <td>{{ $project['wbs'] ?? 'N/A' }}</td>
        <td>{{ Str::limit(trim($project['title'] ?? 'N/A'), 10) }}</td>
        <td>{{ $project['site'] ?? 'N/A' }}</td>
        <td>{!! $project['priority'] ?? 'N/A' !!}</td>
        <td>{{ $project['startDate'] ?? 'N/A' }}</td>
        <td>{{ $project['endDate'] ?? 'N/A' }}</td>
        <td>
            <div style="text-align: center;">
                <div>Dur: {{ $project['duration'] ?? 'N/A' }}</div>
                <div style="color: {{ isset($project['remaining']) && strpos($project['remaining'], 'Past') !== false ? 'red' : 'green' }};">
                    {{ isset($project['remaining']) && strpos($project['remaining'], 'Past') !== false ? '' : 'Rem: ' }} {{ $project['remaining'] ?? 'N/A' }}
                </div>
            </div>
        </td>
        <td style="color: {{ $project['remainingColor'] ?? 'black' }}">{{ $project['remaining'] ?? 'N/A' }}</td>
        <td>{!! $project['status'] ?? 'N/A' !!}</td>
        <td>{{ $project['assignedTo'] ?? 'N/A' }}</td>
        <td>{!! $project['createdBy'] ?? 'N/A' !!}</td>
        <td>{{ $project['createdDate'] ?? 'N/A' }}</td>
        <td>
            <button class="btn btn-circle toggle-tasks badge bg-success" data-id="{{ $project['id'] }}">+</button>
        </td>
    </tr>
    <tr class="tasks-row" data-project-id="{{ $project['id'] }}" style="display: none;">
        <td colspan="14">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Task ID</th>
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
                    @if(isset($project['tasks']) && is_array($project['tasks']) && count($project['tasks']) > 0)
                        @foreach($project['tasks'] as $task)
                            <tr>
                                <td>{{ $task['id'] ?? 'N/A' }}</td>
                                <td>{{ $task['wbs'] ?? 'N/A' }}</td>
                                <td>{{ $task['title'] ?? 'N/A' }}</td>
                                <td>{{ $task['priority'] ?? 'N/A' }}</td>
                                <td>{{ $task['startDate'] ?? 'N/A' }}</td>
                                <td>{{ $task['duration'] ?? 'N/A' }}</td>
                                <td>{{ $task['endDate'] ?? 'N/A' }}</td>
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

                <!-- Pagination Links -->
                {{ $projectsData->appends(request()->query())->links() }}
            </div>
        </div>

        <div class="tab-pane fade" id="navs-top-gantt" role="tabpanel">
            <h3>Gantt Chart</h3>
            <div id="ganttChartContainer">
                <div style="height: 400px; border: 1px solid #ccc; text-align: center; line-height: 400px;">
                    Gantt Chart will be rendered here.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle task visibility
    document.querySelectorAll('.toggle-tasks').forEach(button => {
        button.addEventListener('click', function() {
            const projectId = this.getAttribute('data-id');
            const tasksRow = document.querySelector(`.tasks-row[data-project-id="${projectId}"]`);
            tasksRow.style.display = tasksRow.style.display === 'none' ? '' : 'none';

            // Change button text based on visibility
            this.textContent = tasksRow.style.display === 'none' ? '+' : '-';
        });
    });

    function editProject(id) {
        console.log('Edit project', id);
    }

    function deleteProject(id) {
        console.log('Delete project', id);
    }

    function deleteTask(id) {
        console.log('Delete task', id);
        // Implement actual delete logic here
    }

    function printTable() {
        const printContent = document.querySelector('.table-responsive').innerHTML;
        const newWindow = window.open('', '_blank');
        newWindow.document.write(`
            <html>
                <head>
                    <title>Print Table</title>
                    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                </head>
                <body onload="window.print(); window.close();">
                    <div class="container">
                        <h1>Master Schedule</h1>
                        <table class="table table-bordered">
                            ${printContent}
                        </table>
                    </div>
                </body>
            </html>
        `);
        newWindow.document.close();
    }

    function filterTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase();
        const table = document.getElementById("projectsTable");
        const tr = table.getElementsByTagName("tr");

        for (let i = 1; i < tr.length; i++) { // Start at 1 to skip header row
            const td = tr[i].getElementsByTagName("td");
            let rowContainsSearchTerm = false;

            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    const txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        rowContainsSearchTerm = true;
                        break;
                    }
                }
            }

            tr[i].style.display = rowContainsSearchTerm ? "" : "none";
        }
    }
</script>
@endsection