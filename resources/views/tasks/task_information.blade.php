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
                    <li class="breadcrumb-item"><a href="{{ url('/projects/information/' . $project->id) }}">{{ $project->title }}</a></li>
                @endisset
                <li class="breadcrumb-item active" aria-current="page">{{ get_label('tasks', 'Tasks') }}</li>
            </ol>
        </nav>
        <div>
            @php
                $url = isset($project->id) ? '/projects/tasks/draggable/' . $project->id : '/tasks/draggable';
            @endphp
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_activity_modal" class="btn btn-sm btn-primary" title="{{ get_label('create_Activity', 'Create Activity') }}" data-task-id="{{ $taskId ?? '' }}">
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
    <x-activity-list :taskId="$id" />
</div>
@endsection