@extends('layout')  

@section('title')  
    Master Schedule Overview  
@endsection  

@section('content')  
    <div class="container">  
        <h1>Master Schedule Overview</h1>  

        <!-- Schedule Selection -->  
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

        <!-- Load Gantt dependencies -->  
        <head>  
            <meta http-equiv="Content-type" content="text/html; charset=utf-8">  
            <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>  
            <link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">  
            <style type="text/css">  
                /* Styles for Gantt and Table */
                /* Add your styles here */
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
    </div>  
@endsection