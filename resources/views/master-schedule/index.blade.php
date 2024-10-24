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

    <!-- Custom Modal for Task Creation -->
    <div id="popup" class="modal-popup">
        <h3>Create Task</h3>
        <span class="close-btn" id="closePopup">&times;</span>
        <form>
            <label for="taskName">Task Name:</label>
            <input type="text" id="taskName" required>

            <label for="taskStartDate">Start Date:</label>
            <input type="date" id="taskStartDate" required>

            <label for="taskEndDate">End Date:</label>
            <input type="date" id="taskEndDate" required>

            <label for="taskDuration">Duration:</label>
            <input type="number" id="taskDuration" value="1" min="1" required>

            <label for="taskStatus">Status:</label>
            <select id="taskStatus" required>
                <option value="not-started">Not Started</option>
                <option value="in-progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>

            <label for="taskProgress">Progress (%):</label>
            <input type="number" id="taskProgress" value="0" min="0" max="100" required>

            <label for="taskMember">Team Member:</label>
            <input type="text" id="taskMember" required>

            <label for="taskClient">Client:</label>
            <input type="text" id="taskClient" required>

            <div class="button-group">
                <button type="button" id="createTask">Create</button>
                <button type="button" id="cancel">Cancel</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>  
    <link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">  
    <style>
        /* Modal Styles */
        .modal-popup {
            display: none;
            position: fixed;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            padding: 30px;
            z-index: 1000;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 400px; /* Fixed width for better usability */
            max-width: 90%; /* Responsive max width */
            max-height: 80%; /* Ensure it does not exceed viewport height */
            overflow-y: auto; /* Enable vertical scrolling if content overflows */
            animation: fadeIn 0.3s;
            font-family: 'Arial', sans-serif;
        }

        .modal-popup h3 {
            margin-bottom: 20px;
            text-align: center;
            color: #333;
            font-weight: 600;
        }

        .modal-popup label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .modal-popup input,
        .modal-popup select {
            width: calc(100% - 20px);
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            transition: border 0.3s;
        }

        .modal-popup input:focus,
        .modal-popup select:focus {
            border-color: #007bff; /* Highlight border on focus */
            outline: none; /* Remove default outline */
        }

        .button-group {
            display: flex;
            justify-content: space-between;
        }

        .button-group button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            width: 48%; /* Equal width for buttons */
            transition: background 0.3s;
        }

        .button-group button:hover {
            background-color: #0056b3;
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
            color: #999;
            cursor: pointer;
        }

        .close-btn:hover {
            color: #f00; /* Change color on hover */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>

    <script type="text/javascript">  
        const projects = {  
            data: @json($projects),  
            links: []  
        };  

        gantt.config.date_format = "%Y-%m-%d";  
        gantt.config.details_on_dblclick = true; // Show details on double-click
        gantt.config.open_tree_initially = true; // Open all parent tasks initially
        gantt.config.tree_cell = true; // Enable tree cell for collapsible tasks
        gantt.init("gantt_here");  
        gantt.parse(projects);

        // Open all tasks by default
        projects.data.forEach(task => {
            if (!task.parent) { // Only open top-level tasks
                gantt.open(task.id);
            }
        });

        // Attach click event to tasks
        gantt.attachEvent("onTaskClick", function(id, e) {
            showPopup(id);
            return false; // Prevent default behavior
        });

        // Function to show the popup
        function showPopup(taskId) {
            const task = gantt.getTask(taskId);
            document.getElementById("taskName").value = task.text || ""; // Prepopulate task name
            document.getElementById("taskStartDate").value = task.start_date || ""; // Prepopulate start date
            document.getElementById("taskEndDate").value = task.end_date || ""; // Prepopulate end date
            document.getElementById("taskDuration").value = task.duration || 1; // Prepopulate duration
            document.getElementById("taskStatus").value = task.status || "not-started"; // Prepopulate status
            document.getElementById("taskProgress").value = task.progress || 0; // Prepopulate progress
            document.getElementById("taskMember").value = task.member || ""; // Prepopulate team member
            document.getElementById("taskClient").value = task.client || ""; // Prepopulate client
            
            const popup = document.getElementById("popup");
            popup.style.display = "block";
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
                    closePopup();
                } else {
                    alert('Failed to update task: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function closePopup() {
            document.getElementById("popup").style.display = "none"; // Hide popup
            document.getElementById("taskName").value = ""; // Clear input
            document.getElementById("taskStartDate").value = ""; // Clear start date
            document.getElementById("taskEndDate").value = ""; // Clear end date
            document.getElementById("taskDuration").value = 1; // Reset duration
            document.getElementById("taskStatus").value = "not-started"; // Reset status
            document.getElementById("taskProgress").value = 0; // Reset progress
            document.getElementById("taskMember").value = ""; // Clear member
            document.getElementById("taskClient").value = ""; // Clear client
        }

        document.getElementById("cancel").onclick = closePopup;
        document.getElementById("closePopup").onclick = closePopup; // Close modal when 'X' is clicked

        function updateSchedule() {  
            const year = document.getElementById('year').value;  
            const month = document.getElementById('month').value;  
            const week = document.getElementById('week').value;  

            // Implement filtering logic if needed
        }  
    </script>  
</div>
@endsection