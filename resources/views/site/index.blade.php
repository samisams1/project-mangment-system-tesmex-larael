@extends('layout')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-dark">Our Site Overview</h1>
        <a href="" class="btn btn-primary">Add</a>
    </div>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Location</th>
                <th>Status</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Budget</th>
                <th>Contractor</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($constructionSiteData as $site)
            <tr>
                <td>{{ $site['id'] }}</td>
                <td>{{ $site['name'] }}</td>
                <td>{{ $site['location'] }}</td>
                <td>{{ $site['status'] }}</td>
                <td>{{ $site['start_date'] }}</td>
                <td>{{ $site['end_date'] }}</td>
                <td>{{ number_format($site['budget']) }}</td>
                <td>{{ $site['contractor'] }}</td>
                <td>
                    <a href="" class="btn btn-primary btn-sm">Edit</a>
                    <form action="" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this construction site?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection