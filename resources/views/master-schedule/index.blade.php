@extends('layout')

@section('title')
    {{ get_label('tasks', 'Tasks') }} - {{ get_label('list_view', 'List view') }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
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
                <li class="breadcrumb-item active">{{ get_label('tasks', 'Tasks') }}</li>
            </ol>
        </nav>

        <div>
            @php
                $url = isset($project->id) ? '/projects/tasks/draggable/' . $project->id : '/tasks/draggable';
                $additionalParams = request()->has('project') ? '/projects/tasks/draggable/' . request()->project : '';
                $finalUrl = url($additionalParams ?: $url);
            @endphp

        </div>
        
    </div>

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

        <head>  
            <meta http-equiv="Content-type" content="text/html; charset=utf-8">  
            <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>  
            <link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">  
            <style type="text/css">  
                /* Styles for Gantt and Table */
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }
            </style>  
        </head>  

        <!-- JavaScript to initialize Gantt -->  
        <script type="text/javascript">  
            const projects = {  
                data: @json($projects),  // Pass tasks data from the controller
                links: []  // Define links if needed
            };  

            console.log(projects.data); // Debugging line to check fetched data

            gantt.config.date_format = "%Y-%m-%d";  
            gantt.init("gantt_here");  
            gantt.parse(projects); // Use 'projects' instead of 'tasks'

            function updateSchedule() {  
                const year = document.getElementById('year').value;  
                const month = document.getElementById('month').value;  
                const week = document.getElementById('week').value;  

                filterTasks(year, month, week);  
            }  

            // Add the filterTasks and other necessary functions here...
        </script>  
    <x-activities-card :activities="$activities" :id="$id" :users="$users" :clients="$clients" :projects="$projects" />
   
@endsection
