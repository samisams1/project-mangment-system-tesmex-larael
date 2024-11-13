<div class="mb-3 d-flex justify-content-between align-items-center p-3 bg-light rounded shadow-sm">
    <h5 class="mb-0 text-primary">Task : {{ $taskName }}</h5>
    <p class="text-muted mb-1">The following are the list of tasks for this task:</p>
    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#create_activity_modal" data-task-id="{{ $taskId }}">
        Create Activity  
    </button>
</div>

<div class="activity-table-responsive mt-4">
    <table class="activity-table table table-striped table-hover table-bordered">
        <thead class="table-header-custom">
            <tr>
                <th style="min-width: 50px;">No</th>
                <th style="min-width: 50px;">Wbs</th>
                <th style="min-width: 150px;" title="Activity Name">Activity</th>
                <th style="min-width: 100px;">Unit</th>
                <th style="min-width: 100px;">Quantity</th>
                <th style="min-width: 120px;">Start Date</th>
                <th style="min-width: 120px;">End Date</th>
                <th style="min-width: 100px;" title="Current Status">Status</th>
                <th style="min-width: 120px;" title="Actions for Activity">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($activities) && count($activities) > 0)
                @foreach($activities as $index => $activity)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $projectId }} .{{ $taskId }} . {{ $index + 1 }} </td>
                        <td>{{ $activity['name'] ?? 'N/A' }}</td>
                        <td>{{ $activity['unit'] ?? 'N/A' }}</td>
                        <td>{!! $activity['priority'] !!}</td>
                        <td>{{ \Carbon\Carbon::parse($activity['start_date'] ?? '')->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($activity['end_date'] ?? '')->format('d-m-Y') }}</td>
                        <td>{!! $activity['statusOptions'] !!}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="editActivity({{ $activity['id'] ?? '0' }})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteActivity({{ $activity['id'] ?? '0' }})">Delete</button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9" class="text-center">No activities available for this task.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<style>
    .table-header-custom {
        background-color: #1B8596; /* Set the desired background color */
        color: white; /* Optional: Set text color for better contrast */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var createActivityModal = document.getElementById('create_activity_modal');
        createActivityModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var taskId = button.getAttribute('data-task-id');
            console.log('Task ID:', taskId); // Debugging line
            var taskIdInput = document.getElementById('task_id');
            taskIdInput.value = taskId;
            console.log('Set task_id input value:', taskIdInput.value); // Debugging line
        });
    });

    function resetModalInputs() {
        document.getElementById('createActivityForm').reset();
        document.getElementById('taskId').value = '{{ $taskId }}'; // Reset taskId in case it changes
    }
</script>
