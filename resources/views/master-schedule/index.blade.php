@extends('layout') <!-- Adjust according to your layout -->

@section('content')
<style>
    .table {
        width: 100%;
        table-layout: auto;
    }
    .table th, .table td {
        padding: 0.5rem;
        height: 30px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .table th {
        background-color: #f8f9fa;
        font-weight: bold;
    }
</style>

<div class="container">
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
                </div>
                <div class="input-group mb-3" style="max-width: 200px;">
                    <select class="form-select" id="prioritySelect">
                        <option value="">All Priorities</option>
                        @foreach($priority as $pri)
                            <option value="{{ $pri->id }}">{{ $pri->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group mb-3" style="max-width: 200px;">
                    <select class="form-select" id="statusSelect">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex flex-column flex-md-row align-items-start">
                    <input type="text" class="form-control mb-3 mb-md-0" id="startDate" placeholder="Start Date" style="max-width: 200px;">
                    <input type="text" class="form-control mb-3 mb-md-0 ms-2" id="endDate" placeholder="End Date" style="max-width: 200px;">
                    <button class="btn btn-sm btn-primary ms-2" id="filterBtn">Filter</button>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-hover" id="master-schedule-table">
                    <thead>
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projectsData as $project)
                        <tr>
                            <td>{{ $project['id'] }}</td>
                            <td>{{ $project['wbs'] }}</td>
                            <td>{{ $project['title'] }}</td>
                            <td>{{ $project['site'] }}</td>
                            <td>{!! $project['priority'] !!}</td>
                            <td>{{ $project['startDate'] }}</td>
                            <td>{{ $project['duration'] }}</td>
                            <td>{{ $project['endDate'] }}</td>
                            <td>{!! $project['status'] !!}</td>
                            <td>{{ $project['createdBy'] }}</td>
                            <td>
                                <button class="btn btn-secondary btn-sm" onclick="editProject({{ $project['id'] }})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteProject({{ $project['id'] }})">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between">
                {{ $projectsData->links() }} <!-- Laravel pagination links -->
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter button click event
        document.getElementById('filterBtn').addEventListener('click', function() {
            const search = document.getElementById('searchInput').value;
            const priority = document.getElementById('prioritySelect').value;
            const status = document.getElementById('statusSelect').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            
            fetchProjects(search, priority, status, startDate, endDate);
        });

        // Initial fetch
        fetchProjects();
    });

    function fetchProjects(search = '', priority = '', status = '', startDate = '', endDate = '') {
        const url = new URL('{{ route('master-schedule.index') }}');
        const params = { search, priority, status, startDate, endDate };
        Object.keys(params).forEach(key => params[key] && url.searchParams.append(key, params[key]));

        fetch(url)
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector('#master-schedule-table tbody');
                tbody.innerHTML = '';
                data.projects.forEach(project => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${project.id}</td>
                            <td>${project.wbs}</td>
                            <td>${project.title}</td>
                            <td>${project.site}</td>
                            <td>${project.priority}</td>
                            <td>${project.startDate}</td>
                            <td>${project.duration}</td>
                            <td>${project.endDate}</td>
                            <td>${project.status}</td>
                            <td>${project.createdBy}</td>
                            <td>
                                <button class="btn btn-secondary btn-sm" onclick="editProject(${project.id})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteProject(${project.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                });
                // Handle pagination here based on data.pagination if needed
            });
    }

    function editProject(id) {
        console.log(`Edit project ${id}`);
        // Add your edit logic here
    }

    function deleteProject(id) {
        console.log(`Delete project ${id}`);
        // Add your delete logic here
    }
</script>
@endsection