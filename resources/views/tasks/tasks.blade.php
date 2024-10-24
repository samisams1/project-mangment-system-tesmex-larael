@extends('layout')

@section('title')
    Gantt Chart with Custom Task Creation
@endsection

@section('content')
<div class="container-fluid">
    <h2 class="mt-4">Project Gantt Chart</h2>

    <div>
        <select id="year" onchange="updateSchedule()">  
            @for ($i = 2020; $i <= date('Y'); $i++)  
                <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>  
            @endfor  
        </select>  
        <select id="month" onchange="updateSchedule()">  
            <option value="0">All Months</option>  
            @for ($i = 1; $i <= 12; $i++)  
                <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>  
            @endfor  
        </select>  
        <select id="week" onchange="updateSchedule()">  
            <option value="0">All Weeks</option>  
            @for ($i = 1; $i <= 52; $i++)  
                <option value="{{ $i }}">{{ 'Week ' . $i }}</option>  
            @endfor  
        </select>  
    </div>

    <!-- Gantt Chart -->  
    <div id="gantt_here" style="width:100%; height:400px;"></div>  

    <!-- Bootstrap Modal for Task Creation -->
    <div class="modal fade" id="create_task_modal" tabindex="-1" role="dialog" aria-labelledby="createTaskLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTaskLabel">Create Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="taskForm">
                        <div class="form-group">
                            <label for="taskName">Task Name:</label>
                            <input type="text" class="form-control" id="taskName" required>
                        </div>
                        <div class="form-group">
                            <label for="taskStartDate">Start Date:</label>
                            <input type="date" class="form-control" id="taskStartDate" required>
                        </div>
                        <div class="form-group">
                            <label for="taskEndDate">End Date:</label>
                            <input type="date" class="form-control" id="taskEndDate" required>
                        </div>
                        <div class="form-group">
                            <label for="taskDuration">Duration:</label>
                            <input type="number" class="form-control" id="taskDuration" value="1" min="1" required>
                        </div>
                        <div class="form-group">
                            <label for="taskStatus">Status:</label>
                            <select class="form-control" id="taskStatus" required>
                                <option value="not-started">Not Started</option>
                                <option value="in-progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="taskProgress">Progress (%):</label>
                            <input type="number" class="form-control" id="taskProgress" value="0" min="0" max="100" required>
                        </div>
                        <div class="form-group">
                            <label for="taskMember">Team Member:</label>
                            <input type="text" class="form-control" id="taskMember" required>
                        </div>
                        <div class="form-group">
                            <label for="taskClient">Client:</label>
                            <input type="text" class="form-control" id="taskClient" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="createTask">Create</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>  
    <link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">  
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script type="text/javascript">  
        const projects = {  
            data: @json($projects),  
            links: []  
        };  

        gantt.config.date_format = "%Y-%m-%d";  
        gantt.config.details_on_dblclick = true; // Show details on double-click
        gantt.config.open_tree_initially = false; // Do not open child tasks initially
        gantt.config.tree_cell = true; // Enable tree cell for collapsible tasks
        gantt.init("gantt_here");  
        gantt.parse(projects);

        // Attach click event to tasks
        gantt.attachEvent("onTaskClick", function(id, e) {
            showModal(id);
            return false; // Prevent default behavior
        });

        // Function to show the modal
        function showModal(taskId) {
            const task = gantt.getTask(taskId);
            document.getElementById("taskName").value = task.text || ""; // Prepopulate task name
            document.getElementById("taskStartDate").value = task.start_date || ""; // Prepopulate start date
            document.getElementById("taskEndDate").value = task.end_date || ""; // Prepopulate end date
            document.getElementById("taskDuration").value = task.duration || 1; // Prepopulate duration
            document.getElementById("taskStatus").value = task.status || "not-started"; // Prepopulate status
            document.getElementById("taskProgress").value = task.progress || 0; // Prepopulate progress
            document.getElementById("taskMember").value = task.member || ""; // Prepopulate team member
            document.getElementById("taskClient").value = task.client || ""; // Prepopulate client
            
            // Show the modal
            $('#create_task_modal').modal('show');

            document.getElementById("createTask").onclick = function() { createTask(taskId); };
        }

        function createTask(taskId) {
            const taskData = {
                id: taskId, // Update existing task
                text: document.getElementById("taskName").value,
                start_date: document.getElementById("taskStartDate").value,
                end_date: document.getElementById("taskEndDate").value,
                duration: document.getElementById("taskDuration").value,
                status: document.getElementById("taskStatus").value,
                progress: document.getElementById("taskProgress").value,
                member: document.getElementById("taskMember").value,
                client: document.getElementById("taskClient").value,
            };

            fetch('/tasks/update', { // Change this to your update endpoint
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(taskData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    gantt.updateTask(taskId); // Update task in Gantt
                    $('#create_task_modal').modal('hide'); // Hide the modal
                    clearForm();
                } else {
                    alert('Failed to update task: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function clearForm() {
            document.getElementById("taskForm").reset(); // Clear the form fields
        }

        function updateSchedule() {  
            const year = document.getElementById('year').value;  
            const month = document.getElementById('month').value;  
            const week = document.getElementById('week').value;  

            // Implement filtering logic if needed
        }  
    </script>  
</div>
@endsection