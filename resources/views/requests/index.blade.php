@extends('layout')

@section('title')
<?= get_label('tasks', 'Tasks') ?> - <?= get_label('list_view', 'List view') ?>
@endsection

@section('content')
    <div class="container">
        <h1>Requests</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('requests.create') }}" class="btn btn-primary mb-3">Create New Request</a>

        <table class="table">
            <thead>
                <tr>
                    <th>Requested By</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>priority</th>
                    <th>Status</th>
                    <th>Replay<th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($requests as $request)
                    <tr>
                        <td>{{ $request['title'] }}</td>
                        <td>{{ $request['description'] }}</td>
                        <td>{{ $request['description'] }}</td>
                        <td>{{ $request['description'] }}</td>
                        <td>{{ $request['description'] }}</td>
                        <td>{{ $request['description'] }}</td>
                        <td>{{ $request['description'] }}</td>
                        <td>{{ $request['description'] }}</td>
                        <td>{{ $request['created_at'] }}</td>
                        <td>
                            <a href="{{ route('requests.show', $request['id']) }}" class="btn btn-sm btn-info">View</a>
                            <!-- Add more actions as needed -->
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection