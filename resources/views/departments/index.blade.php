@extends('layout')

@section('title')
    {{ get_label('departments', 'Departments') }} - {{ get_label('list_view', 'List view') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-2 mt-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ url('/departments') }}">{{ get_label('departments', 'Departments') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ get_label('list', 'List') }}</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_department_modal">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="{{ get_label('create_Department', 'Create Department') }}">
                        <i class='bx bx-plus'></i>
                    </button>
                </a>
                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_project_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_project', 'Create project') ?>"><i class='bx bx-plus'></i></button></a>
            </div>
            <input type="hidden" id="type">
            <input type="hidden" id="typeId">
        </div>

        <x-departments-card :departments="$departments" />
    </div>
@endsection