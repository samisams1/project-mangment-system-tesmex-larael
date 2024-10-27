<div class="card">
    <div class="card-body">
        <div class="d-flex flex-column flex-md-row justify-content-between mb-3">
            <div class="mb-3 mb-md-0">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#create_project_modal">
                    Create Project
                </button>
            </div>
            <div class="input-group mb-3" style="max-width: 300px;">
                <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                <button class="btn btn-outline-secondary" type="button" id="searchButton">Search</button>
            </div>
            <div class="d-flex flex-column flex-md-row align-items-start">
                <input type="text" class="form-control datepicker mb-3 mb-md-0" id="datePicker" placeholder="Select Date" style="max-width: 200px;">
                <div class="btn-group ms-2">
                    <button class="btn btn-sm btn-primary" id="exportPDF" type="button">Export PDF</button>
                    <button class="btn btn-sm btn-primary" id="exportCSV" type="button">Export CSV</button>
                    <button class="btn btn-sm btn-primary" id="printReport" type="button">Print</button>
                </div>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-bordered" id="master-schedule-table">
                <thead class="tablehead">
                    <tr>
                        <th>ID</th>
                        <th>WBS</th>
                        <th>Project</th>
                        <th>Site</th>
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
                    <script>
                        const projectsData = @json($projectsData);
                    </script>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const tbody = document.querySelector('#master-schedule-table tbody');
                            tbody.innerHTML = renderProjects(projectsData);

                            tbody.addEventListener('click', function(e) {
                                if (e.target.classList.contains('toggle-tasks')) {
                                    const projectId = e.target.getAttribute('data-id');
                                    const tasksRow = tbody.querySelector(`.tasks-row[data-project-id="${projectId}"]`);
                                    tasksRow.style.display = tasksRow.style.display === 'none' ? '' : 'none';
                                    e.target.textContent = tasksRow.style.display === 'none' ? '+' : '-';
                                }

                                if (e.target.classList.contains('add-activity')) {
                                    const taskId = e.target.getAttribute('data-id');
                                    document.getElementById('taskId').value = taskId;
                                    const createActivityModal = new bootstrap.Modal(document.getElementById('create_activity_modal'));
                                    createActivityModal.show();
                                }
                            });

                            // Event listeners for export buttons
                            document.getElementById('exportPDF').addEventListener('click', function() {
                                window.location.href = '{{ route("projects.pdf") }}'; // Update with your route
                            });

                            document.getElementById('exportCSV').addEventListener('click', function() {
                                window.location.href = '{{ route("projects.export.csv") }}'; // Update with your route
                            });
                        });

                        function renderProjects(rows) {
                            let html = '';
                            rows.forEach(row => {
                                html += `
                                    <tr class="project-row">
                                        <td>${row.id} 
                                            <button class="btn btn-circle toggle-tasks badge bg-success" data-id="${row.id}">+</button>
                                        </td>
                                        <td>${row.wbs}</td>
                                        <td>${row.title.trim().length > 10 ? row.title.trim().slice(0, 10) + '...' : row.title.trim()}</td>
                                        <td>${row.site}</td>
                                        <td>${row.priority}</td>
                                        <td>${row.startDate}</td>
                                        <td>
                                            <div style="text-align: center;">
                                                <div>dur ${" " + row.duration}</div>
                                                <div style="color: ${row.remaining.includes("Pas") ? 'red' : 'green'};">
                                                    ${row.remaining.includes("Pas") ? '' : 'Rem'} ${" " + row.remaining}
                                                </div>
                                            </div>
                                        </td>
                                        <td>${row.endDate}</td>
                                        <td>${row.status}</td>
                                        <td>${row.assignedTo}</td>
                                        <td>${row.createdBy}</td>
                                        <td>${row.createdDate}</td>
                                        <td>
                                            <button class="btn btn-secondary btn-sm add-task" data-id="${row.id}">Add Task</button>
                                        </td>
                                    </tr>
                                    <tr class="tasks-row" data-project-id="${row.id}" style="display: none;">
                                        <td colspan="12">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Task ID</th>
                                                        <th>WBS</th>
                                                        <th>Task</th>
                                                        <th>Site</th>
                                                        <th>Priority</th>
                                                        <th>Start Date</th>
                                                        <th>End Date</th>
                                                        <th>Status</th>
                                                        <th>Assigned To</th>
                                                        <th>Created By</th>
                                                        <th>Created Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${renderTasks(row.tasks)}
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                `;
                            });
                            return html;
                        }

                        function renderTasks(tasks) {
                            return tasks.map(task => `
                                <tr class="task-row">
                                    <td>${task.id} 
                                        <button class="btn btn-circle toggle-activities badge bg-primary" data-id="${task.id}">+</button>
                                    </td>
                                    <td>${task.wbs}</td>
                                    <td>${task.title}</td>
                                    <td>${task.site}</td>
                                    <td>${task.priority}</td>
                                    <td>${task.startDate}</td>
                                    <td>${task.endDate}</td>
                                    <td>${task.status}</td>
                                    <td>${task.assignedTo}</td>
                                    <td>${task.createdBy}</td>
                                    <td>${task.createdDate}</td>
                                    <td>
                                        <button class="btn btn-secondary btn-sm add-activity" data-id="${task.id}">Add Activity</button>
                                        <button class="btn btn-danger btn-sm delete-task" data-id="${task.id}">Delete Task</button>
                                    </td>
                                </tr>
                                <tr class="activities-row" data-task-id="${task.id}" style="display: none;">
                                    <td colspan="12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Activity ID</th>
                                                    <th>Description</th>
                                                    <th>Assigned To</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${renderActivities(task.activities)}
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            `).join('');
                        }

                        function renderActivities(activities) {
                            return activities.map(activity => `
                                <tr>
                                    <td>${activity.id}</td>
                                    <td>${activity.description}</td>
                                    <td>${activity.assignedTo}</td>
                                    <td>${activity.status}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm delete-activity" data-id="${activity.id}">Delete Activity</button>
                                    </td>
                                </tr>
                            `).join('');
                        }
                    </script>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Activity Modal -->
<div class="modal fade" id="create_activity_modal" tabindex="-1" aria-labelledby="createActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createActivityModalLabel">{{ get_label('create_activity', 'Create Activity') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createActivityForm" method="POST" action="{{ route('activities.store') }}" onsubmit="resetModalInputs()">
                    @csrf
                    <input type="hidden" id="taskId" name="task_id" value="">

                    <div class="mb-3">
                        <label for="activityName" class="form-label">{{ get_label('activity_name', 'Activity Name') }}</label>
                        <input type="text" class="form-control" id="activityName" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="activityPriority" class="form-label">{{ get_label('activity_priority', 'Activity Priority') }}</label>
                        <select class="form-select" id="activityPriority" name="priority" required>
                            <option value="" disabled selected>{{ get_label('select_priority', 'Select Priority') }}</option>
                            @foreach($priority as $pri)
                                <option value="{{ $pri->id }}">{{ $pri->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="status">{{ get_label('status', 'Status') }} <span class="asterisk">*</span></label>
                        <select class="form-select" name="status_id" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}">{{ $status->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="user_id">{{ get_label('select_members', 'Select members') }}</label>
                        <select class="form-control js-example-basic-multiple" name="user_id[]" multiple="multiple" data-placeholder="{{ get_label('type_to_search', 'Type to search') }}">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ (collect(old('user_id'))->contains($user->id)) ? 'selected' : '' }}>{{ $user->first_name }} {{ $user->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="start_date">{{ get_label('starts_at', 'Starts at') }} <span class="asterisk">*</span></label>
                        <input type="date" id="start_date" name="start_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="ends_at">{{ get_label('ends_at', 'Ends at') }} <span class="asterisk">*</span></label>
                        <input type="date" id="ends_at" name="ends_at" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ get_label('submit', 'Submit') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Set default dates when the modal is shown
    document.getElementById('create_activity_modal').addEventListener('show.bs.modal', function () {
        const today = new Date().toISOString().split('T')[0]; // Get current date in YYYY-MM-DD format
        document.getElementById('start_date').value = today; // Set start date
        document.getElementById('ends_at').value = today; // Set end date
    });

    function resetModalInputs() {
        // Reset form fields after submission
        document.getElementById('createActivityForm').reset();
        document.getElementById('taskId').value = ''; // Reset the task ID
    }
</script>

<style>
    .tablehead {
        background-color: #1B8596; /* Your preferred header background color */
        color: white; /* White text color for header */
    }
    .table:not(.table-dark) th {
    color: #d8dde2 !important;
}
</style>