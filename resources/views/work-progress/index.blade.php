@extends('layout')

@section('content')
<div class="container my-5">
    <h1 class="text-center mb-4 text-dark">Work Progress Dashboard</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    Today's Progress
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="text-dark">Completed</h5>
                            <span class="badge badge-success text-dark" style="background-color: green;">{{ $completed }}</span>
                        </div>
                        <div class="col-6">
                            <h5 class="text-dark">Not Started</h5>
                            <span class="badge badge-success text-dark" style="background-color: red;">{{ $notStarted }}</span>
                        </div>
                        <div class="col-6">
                            <h5 class="text-dark">Started</h5>
                            <span class="badge badge-success text-dark" style="background-color: yellow;">{{ $started }}</span>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <h5 class="text-dark">Ian Progress</h5>
                            <span class="badge badge-warning text-dark" style="background-color: orange;">{{ $inProgress }}</span>
                        </div>
                        <div class="col-6">
                            <h5 class="text-dark">Total</h5>
                            <span class="badge badge-primary text-dark">{{ $total }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    Top Employer of the week
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-6">
                            <h6 class="text-dark">Melamu Mamo</h6>
                            <h6 class="text-dark">grum</h6>
                            <h6 class="text-dark">grum</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Employee Progress
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-dark">No</th>
                                <th class="text-dark">User</th>
                                <th class="text-dark">Status</th>
                                <th class="text-dark">Issue</th>
                                <th class="text-dark">Badge</th>
                                <th class="text-dark">Remark</th>
                                <th class="text-dark">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $key => $user)
                            <tr>
                                <td class="text-dark">{{ $key + 1 }}</td>
                                <td class="text-dark">{{ $user->name }}</td>
                                <td class="text-dark">
                                    @if ($user->status == 'not started')
                                        <span class="badge badge-success text-dark" style="background-color: red;">{{ $user->status }}</span>
                                    @elseif ($user->status == 'started')
                                        <span class="badge badge-success text-dark" style="background-color: orange;">{{ $user->status }}</span>
                                    @elseif ($user->status == 'in progress')
                                        <span class="badge badge-success text-dark" style="background-color: yellow;">{{ $user->status }}</span>
                                    @elseif ($user->status == 'completed')
                                        <span class="badge badge-success text-dark" style="background-color: green;">{{ $user->status }}</span>
                                    @else
                                        <span class="badge badge-success text-dark" style="background-color: black;">{{ $user->status }}</span>
                                    @endif
                                </td>
                                <td class="text-dark"><span class="badge badge-warning text-dark">{{ $user->issue }}</span></td>
                                <td class="text-dark"><span class="badge badge-danger text-dark">{{ $user->bage }}</span></td>
                                <td class="text-dark"><span class="badge badge-warning text-dark">{{ $user->remark }}</span></td>
                                <td class="text-dark">
                                <a href="{{ route('work-progress.showWorkProgress') }}" class="btn btn-primary btn-sm">Detail</a>
                            </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    Work Progress Graph
                </div>
                <div class="card-body">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Completed', 'Not Started', 'Started', 'In Progress'],
            datasets: [{
                label: 'Work Progress',
                data: [{{ $completed }}, {{ $notStarted }}, {{ $started }}, {{ $inProgress }}],
                backgroundColor: [
                    'green',
                    'red',
                    'orange',
                    'yellow'
                ],
                borderColor: [
                    'green',
                    'red',
                    'orange',
                    'yellow'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection