<div class="tab-pane fade active show" id="navs-top-schedule" role="tabpanel">
    <!-- Filter Section -->
    <div class="mb-4">
        <form method="GET" action="{{ route('master-schedule.index') }}" class="row g-3">
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Select Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>{{ $status->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
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
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>
    </div>

    <!-- Action Buttons -->
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create_project_modal">
                Create Project
            </button>
            <input type="text" id="searchInput" class="form-control d-inline-block ms-2" placeholder="Search..." onkeyup="filterTable()" style="width: 200px;">
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
                        <td>{{ $project['assignedTo'] ?? 'N/A' }}</td>
                        <td>{!! $project['createdBy'] ?? 'N/A' !!}</td>
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
                            <div class="mb-2">
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#create_task_modal">
                                    Create Task  on project {{$project['id']}}
                                </button>
                            </div>
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
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2">{{ $task['id'] ?? 'N/A' }}</span>
                                                        <button class="btn btn-circle toggle-activities badge bg-primary" data-id="{{ $task['id'] }}" onclick="toggleActivities({{ $task['id'] }}, this)">+</button>
                                                    </div>
                                                </td>
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
                                                    <div class="mb-2">
                                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#create_activity_modal">
                                                            Create Activity
                                                        </button>
                                                    </div>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Activity</th>
                                                                <th>Assigned To</th>
                                                                <th>Status</th>
                                                                <th>Created Date</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if(isset($task['activities']) && count($task['activities']) > 0)
                                                                @foreach($task['activities'] as $activity)
                                                                    <tr>
                                                                        <td>{{ $activity['id'] ?? 'N/A' }}</td>
                                                                        <td>{{ $activity['description'] ?? 'N/A' }}</td>
                                                                        <td>{{ $activity['assignedTo'] ?? 'N/A' }}</td>
                                                                        <td>{{ $activity['status'] ?? 'N/A' }}</td>
                                                                        <td>{{ \Carbon\Carbon::parse($activity['createdDate'] ?? '')->format('d-m-Y') }}</td>
                                                                        <td>
                                                                            <button class="btn btn-danger" onclick="deleteActivity({{ $activity['id'] ?? '0' }})">Delete</button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td colspan="6" class="text-center">No activities available for this task.</td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
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
<div class="modal fade" id="create_task_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="/tasks/store" class="form-submit-event modal-content" method="POST">
            @if (!Request::is('projects/tasks/draggable/*') && !Request::is('tasks/draggable'))
            <input type="hidden" name="dnr">
            <input type="hidden" name="table" value="task_table">
            @endif
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_task', 'Create Task') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label for="title" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" value="{{ old('title') }}">
                        @error('title')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="status"><?= get_label('status', 'Status') ?> <span class="asterisk">*</span></label>
                        <div class="input-group">

                            <select class="form-select" name="status_id">
                                @foreach($statuses as $status)
                                <option value="{{$status->id}}" class="badge bg-label-{{$status->color}}" {{ old('status') == $status->id ? "selected" : "" }}>{{$status->title}} ({{$status->color}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_status_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_status', 'Create status') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/status/manage" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_statuses', 'Manage statuses') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                        @error('status_id')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label"><?= get_label('priority', 'Priority') ?></label>
                        <div class="input-group">

                            <select class="form-select" name="priority_id">
                                @foreach($priorities as $priority)
                                <option value="{{$priority->id}}" class="badge bg-label-{{$priority->color}}" {{ old('priority') == $priority->id ? "selected" : "" }}>{{$priority->title}} ({{$status->color}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_priority_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_priority', 'Create Priority') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/priority/manage" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_priorities', 'Manage Priorities') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                        @error('priority_id')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="start_date"><?= get_label('starts_at', 'Starts at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="task_start_date" name="start_date" class="form-control" value="">
                        @error('start_date')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="due_date"><?= get_label('ends_at', 'Ends at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="task_end_date" name="due_date" class="form-control" value="">
                        @error('due_date')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

         
                <div class="row" id="selectTaskUsers">
                    <div class="mb-3">
                        <label class="form-label" for="user_id"><?= get_label('select_users', 'Select users') ?> <span id="users_associated_with_project"></span><?php if (!empty($project_id)) { ?> (<?= get_label('users_associated_with_project', 'Users associated with project') ?> <b>{{$project->title}}</b>)

                            <?php } ?></label>
                        <div class="input-group">
                            <select id="" class="form-control js-example-basic-multiple" name="user_id[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                <?php if (!empty($project_id)) { ?>
                                    @foreach($toSelectTaskUsers as $user)
                                    <?php
                                    $selected = '';
                                    // Check if task_accessibility is 'project_users' or if the user is the authenticated user
                                    if ($project->task_accessibility == 'project_users' || $user->id == getAuthenticatedUser()->id) {
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="{{$user->id}}" {{ (collect(old('user_id'))->contains($user->id)) ? 'selected':'' }} <?= $selected ?>>{{$user->first_name}} {{$user->last_name}}</option>
                                    @endforeach
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="mb-3">
                        <label for="description" class="form-label"><?= get_label('description', 'Description') ?> <span class="asterisk">*</span></label>
                        <textarea class="form-control" rows="5" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>
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
    /* Adjust column widths for better layout */
    #projectsTable td:nth-child(1),
    #projectsTable td:nth-child(2),
    #projectsTable td:nth-child(3),
    #projectsTable td:nth-child(4),
    #projectsTable td:nth-child(5),
    #projectsTable td:nth-child(10) {
        width: 100px;
    }
    #projectsTable td:nth-child(6),
    #projectsTable td:nth-child(7) {
        width: 120px;
    }
</style>

<script>
    function toggleTasks(projectId, button) {
        const tasksRow = document.getElementById(`tasks-${projectId}`);
        if (tasksRow.style.display === "none") {
            tasksRow.style.display = "table-row"; // Show the tasks
            button.innerHTML = '-'; // Change to minus button
        } else {
            tasksRow.style.display = "none"; // Hide the tasks
            button.innerHTML = '+'; // Change back to plus button
        }
    }

    function toggleActivities(taskId, button) {
        const activitiesRow = document.getElementById(`activities-${taskId}`);
        if (activitiesRow.style.display === "none") {
            activitiesRow.style.display = "table-row"; // Show the activities
            button.innerHTML = '-'; // Change to minus button
        } else {
            activitiesRow.style.display = "none"; // Hide the activities
            button.innerHTML = '+'; // Change back to plus button
        }
    }

    function deleteTask(taskId) {
        // Implement delete task functionality
        console.log(`Delete task with ID: ${taskId}`);
    }

    function deleteActivity(activityId) {
        // Implement delete activity functionality
        console.log(`Delete activity with ID: ${activityId}`);
    }

    function filterTable() {
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


