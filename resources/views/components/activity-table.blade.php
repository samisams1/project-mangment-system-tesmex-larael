<h3>Activities for Task ID: {{ $taskId }}</h3>

<div class="mb-2">
    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#create_activity_modal">
        Create Activity  
    </button> 
</div>

<div class="activity-table-responsive mt-4">
    <table class="activity-table table table-striped table-hover table-bordered">
        <thead class="thead-dark">
            <tr>
                <th style="min-width: 50px;">No</th>
                <th style="min-width: 150px;" title="Activity Name">Activity</th>
                <th style="min-width: 100px;">Unit</th>
                <th style="min-width: 100px;">Quantity</th>
                <th style="min-width: 100px;">Priority</th>
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
                        <td>{{ $activity['name'] ?? 'N/A' }}</td>
                        <td>{{ $activity['unit'] ?? 'N/A' }}</td>
                        <td>{{ $activity['quantity'] ?? 'N/A' }}</td>
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
                    <input type="hidden" id="taskId" name="task_id" value="{{ $taskId }}">

                    <div class="mb-3">
                        <label for="activityName" class="form-label">{{ get_label('activity_name', 'Activity Name') }}</label>
                        <input type="text" class="form-control" id="activityName" name="name" required>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="unit" class="form-label">{{ get_label('unit', 'Unit') }}</label>
                            <select class="form-select" id="unit" name="unit" required>
                                <option value="" disabled selected>{{ get_label('select_unit', 'Select Unit') }}</option>
                                @foreach($statuses as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">{{ get_label('quantity', 'Quantity') }}</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required min="1">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="activityPriority" class="form-label">{{ get_label('activity_priority', 'Activity Priority') }}</label>
                            <select class="form-select" id="activityPriority" name="priority" required>
                                <option value="" disabled selected>{{ get_label('select_priority', 'Select Priority') }}</option>
                                @foreach($statuses as $pri)
                                    <option value="{{ $pri->id }}">{{ $pri->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">{{ get_label('status', 'Status') }} <span class="asterisk">*</span></label>
                            <select class="form-select" name="status_id" required>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="user_id">{{ get_label('select_members', 'Select members') }}</label>
                        <select class="form-control js-example-basic-multiple" name="user_id[]" multiple="multiple" data-placeholder="{{ get_label('type_to_search', 'Type to search') }}">
                            @foreach($statuses as $user)
                                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label class="form-label" for="start_date">{{ get_label('starts_at', 'Starts at') }} <span class="asterisk">*</span></label>
                            <input type="text" id="task_start_date" name="start_date" class="form-control" required>
                            @error('start_date')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="due_date">{{ get_label('ends_at', 'Ends at') }} <span class="asterisk">*</span></label>
                            <input type="text" id="task_end_date" name="due_date" class="form-control" required>
                            @error('due_date')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ get_label('submit', 'Submit') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function resetModalInputs() {
        document.getElementById('createActivityForm').reset();
        document.getElementById('taskId').value = '{{ $taskId }}'; // Reset taskId in case it changes
    }
</script>