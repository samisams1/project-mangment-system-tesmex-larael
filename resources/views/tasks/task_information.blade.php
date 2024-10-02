@extends('layout')

@section('title')
{{ get_label('tasks', 'Tasks') }} - {{ get_label('list_view', 'List view') }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1">
                <li class="breadcrumb-item">
                    <a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a>
                </li>
                @isset($project->id)
                <li class="breadcrumb-item">
                    <a href="{{ url('/projects') }}">{{ get_label('projects', 'Projects') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/projects/information/'.$project->id) }}">{{ $project->title }}</a>
                </li>
                @endisset
                <li class="breadcrumb-item active" aria-current="page">{{ get_label('tasks', 'Tasks') }}</li>
            </ol>
        </nav>

        <div>
            @php
            $url = isset($project->id) ? '/projects/tasks/draggable/' . $project->id : '/tasks/draggable';
            @endphp

            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_activity_modal" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ get_label('create_Activity', 'Create Activity') }}">
                <i class="bx bx-plus"></i> {{ get_label('create_Activity', 'Create Activity') }}
            </a>
        </div>
    </div>

    <div class="row mb-4">
        @foreach (['completed' => '#71dd37', 'in_progress' => '#696cff', 'not_started' => '#ffab00', 'cancelled' => '#ff3e1d'] as $status => $color)
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="avatar flex-shrink-0 mb-2">
                        <i class="menu-icon tf-icons bx bx-briefcase-alt-2 bx-md" style="color: {{ $color }};"></i>
                    </div>
                    <span class="fw-semibold d-block mb-1">{{ get_label($status, ucfirst(str_replace('_', ' ', $status))) }}</span>
                    <h3 class="card-title mb-2">2</h3>
                    <a href="/tasks/{{ $status }}" class="text-decoration-none" style="color: {{ $color }};">
                        <small><i class="bx bx-right-arrow-alt"></i> {{ get_label('view_more', 'View more') }}</small>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div>
        <div class="mb-3">
            <input type="text" id="dateRange" class="form-control" placeholder="Select date range" />
        </div>
        <table class="table table-striped table-bordered" id="tasksTable">
            <thead class="table-header">
                <tr>
                    <th>No</th>
                    <th>{{ get_label('activity_name', 'Activity Name') }}</th>
                    <th>{{ get_label('status', 'Status') }}</th>
                    <th>{{ get_label('priority', 'Priority') }}</th>
                    <th>{{ get_label('start_date', 'Start Date') }}</th>
                    <th>{{ get_label('end_date', 'End Date') }}</th>
                    <th>{{ get_label('progress', 'Progress') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $detail)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $detail['task_name'] }}</td>
                    <td>{{ trim($detail['status']) }}</td>
                    <td>{{ $detail['priority'] }}</td>
                    <td>{{ $detail['start_date'] }}</td>
                    <td>{{ $detail['end_date'] ?? 'N/A' }}</td>
                    <td>{{ $detail['progress'] }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
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
                        <input type="hidden" class="form-control" id="taskId" name="task_id" value="{{ $task->id }}">  
                        <div class="mb-3">  
                            <label for="activityName" class="form-label">{{ get_label('activity_name', 'Activity Name') }}</label>  
                            <input type="text" class="form-control" id="activityName" name="name" required>  
                        </div>   
                        <div class="mb-3">  
                            <label for="activityPriority" class="form-label">{{ get_label('activity_priority', 'Activity Priority') }}</label>  
                            <input type="number" class="form-control" id="activityPriority" name="priority" required>  
                        </div>  
                        <div class="mb-3">  
                            <label for="startDate" class="form-label">{{ get_label('start_date', 'Start Date') }}</label>  
                            <input type="date" class="form-control" id="startDate" name="start_date">  
                        </div>  
                        <div class="mb-3">  
                            <label for="endDate" class="form-label">{{ get_label('end_date', 'End Date') }}</label>  
                            <input type="date" class="form-control" id="endDate" name="end_date">  
                        </div>  
                        <button type="submit" class="btn btn-primary">{{ get_label('submit', 'Submit') }}</button>  
                    </form>  
                </div>  
            </div>  
        </div>  
    </div>

    <div class="chart-row">
        <div class="chart-container">
            <canvas id="taskBarChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="taskPieChart"></canvas>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        const table = $('#tasksTable').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            lengthMenu: [10, 50, 250, 500],
            pageLength: 10,
            dom: `<"top"lfB>rt<"bottom"ip><"clear">`,
            buttons: [
                {
                    extend: 'copyHtml5',
                    text: 'Copy',
                    className: 'btn btn-secondary'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Export PDF',
                    title: 'Resource Allocation',
                    className: 'btn btn-success'
                },
                {
                    extend: 'csvHtml5',
                    text: 'Export CSV',
                    title: 'Resource Allocation',
                    className: 'btn btn-info'
                },
                {
                    extend: 'print',
                    text: 'Print',
                    className: 'btn btn-primary'
                }
            ],
            language: {
                search: "Filter records:",
                lengthMenu: "Display _MENU_ records per page"
            }
        });

        // Date range picker initialization
        $('#dateRange').daterangepicker({
            opens: 'left',
            locale: {
                format: 'YYYY-MM-DD'
            }
        });

        // Apply filter on date range selection
        $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
            const startDate = picker.startDate.format('YYYY-MM-DD');
            const endDate = picker.endDate.format('YYYY-MM-DD');

            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    const date = new Date(data[4]); // Assuming the start date is in the 5th column (index 4)
                    return date >= new Date(startDate) && date <= new Date(endDate);
                }
            );

            table.draw();
        });

        // Reset filter when date range is cleared
        $('#dateRange').on('cancel.daterangepicker', function() {
            $.fn.dataTable.ext.search.pop();
            table.draw();
        });
    });

    function resetModalInputs() {
        // Clear the input fields after submission
        setTimeout(() => {
            document.getElementById('createActivityForm').reset();
        }, 500);
    }
</script>

<style>
    .chart-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 40px;
    }

    .chart-container {
        flex: 1 1 100%;
        margin-bottom: 20px;
        height: 400px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    @media (min-width: 768px) {
        .chart-container {
            flex-basis: 50%;
            max-width: 50%;
        }
    }

    .table-header {
        background-color: #007bff; /* Blue color for the table header */
        color: white !important;
    }

    .table th {
        padding: 10px !important;
        font-weight: bold !important;
    }

    .table td {
        vertical-align: middle !important;
        padding: 8px !important;
        background-color: #f8f9fa !important;
    }

    .table tbody tr:hover {
        background-color: #e2f0e7 !important;
    }

    .dt-buttons {
        margin-bottom: 10px;
    }

    .dataTables_filter {
        display: flex;
        align-items: center;
        margin-left: auto;
    }

    .dataTables_filter input {
        margin-left: 0.5rem;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection