@extends('layout')

@section('title')
{{ get_label('tasks', 'Tasks') }} - {{ get_label('list_view', 'List view') }}
@endsection

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb Navigation -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a></li>
                @isset($project->id)
                    <li class="breadcrumb-item"><a href="{{ url('/projects') }}">{{ get_label('projects', 'Projects') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/projects/information/'.$project->id) }}">{{ $project->title }}</a></li>
                @endisset
                <li class="breadcrumb-item active" aria-current="page">{{ get_label('tasks', 'Tasks') }}</li>
            </ol>
        </nav>
        <div>
            @php
                $url = isset($project->id) ? '/projects/tasks/draggable/' . $project->id : '/tasks/draggable';
            @endphp
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_activity_modal" class="btn btn-sm btn-primary" title="{{ get_label('create_Activity', 'Create Activity') }}">
                <i class="bx bx-plus"></i> {{ get_label('create_Activity', 'Create Activity') }}
            </a>
        </div>
    </div>

    <!-- Tasks Overview -->
    <div class="row mb-4">
        @foreach ($statusData as $status => $dtatusdata)
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="avatar flex-shrink-0 mb-2">
                        <i class="menu-icon tf-icons bx bx-briefcase-alt-2 bx-md" style="color: {{ $dtatusdata['color'] }};"></i>
                    </div>
                    <span class="fw-semibold d-block mb-1">{{ get_label($status, ucfirst(str_replace('_', ' ', $status))) }}</span>
                    <h3 class="card-title mb-2">{{ $dtatusdata['count'] }}</h3>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div>
        <form action="{{ route('activity.checklist') }}" method="POST" id="materialForm">
            @csrf
            <table class="table table-striped table-bordered" id="tasksTable">
                <thead class="table-header">
                    <tr>
                        <th>No</th>
                        <th>wbs</th>
                        <th>{{ get_label('activity_name', 'Activity Name') }}</th>
                        <th>{{ get_label('status', 'Status') }}</th>
                        <th>{{ get_label('priority', 'Priority') }}</th>
                        <th>{{ get_label('start_date', 'Start Date') }}</th>
                        <th>{{ get_label('end_date', 'End Date') }}</th>
                        <th>{{ get_label('progress', 'Progress') }}</th>
                        <th><input type="checkbox" id="selectAllCheckbox" /></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subtasks as $key => $detail)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $detail->wbs }}</td>
                        <td>{{ $detail['title'] }}</td>
                        <td><span class='badge bg-label-{{ trim($detail['status_color']) }}'>{{ trim($detail['status']) }}</span></td>
                        <td><span class='badge bg-label-{{ trim($detail['priority_color']) }}'>{{ $detail['priority'] }}</span></td>
                        <td>{{ $detail['start_date'] }}</td>
                        <td>{{ $detail['end_date'] }}</td>
                        <td>{{ $detail['progress'] }}%</td>
                        <td>
                            <input type="checkbox" class="task-checkbox" name="selected_tasks[]" value="{{ $detail['id'] }}" />
                            <input type="hidden" name="activity_name[{{ $detail['id'] }}]" value="{{ $detail['activity_name'] }}" />
                            <input type="hidden" name="wbs[{{ $detail['id'] }}]" value="{{ $detail['wbs'] }}" />
                            <input type="hidden" name="status[{{ $detail['id'] }}]" value="{{ $detail['status'] }}" />
                            <input type="hidden" name="priority[{{ $detail['id'] }}]" value="{{ $detail['priority'] }}" />
                            <input type="hidden" name="start_date[{{ $detail['id'] }}]" value="{{ $detail['start_date'] }}" />
                            <input type="hidden" name="end_date[{{ $detail['id'] }}]" value="{{ $detail['end_date'] }}" />
                            <input type="hidden" name="progress[{{ $detail['id'] }}]" value="{{ $detail['progress'] }}" />
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-success">Submit Selected</button>
        </form>
    </div>

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

                    <button type="submit" class="btn btn-primary">{{ get_label('submit', 'Submit') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Select/Deselect all checkboxes
            $('#selectAllCheckbox').on('change', function() {
                $('.task-checkbox').prop('checked', this.checked);
            });
        });

        function resetModalInputs() {
            setTimeout(() => {
                $('#createActivityForm')[0].reset();
            }, 500);
        }
    </script>

    <style>
        .table-header {
            background-color: #1B8596; /* Customize as needed */
            color: white !important;
        }
    </style>
</div>
@endsection