@extends('layout')

@section('title')
<?= get_label('sub tasks', 'Sub Tasks') ?> - <?= get_label('list_view', 'List view') ?>
@endsection

@section('content')
    <div class="container">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
<!-- resources/views/materials/index.blade.php -->

<h1>Labor</h1>

<a href="{{ route('materials.create') }}" class="btn btn-primary">Create Material</a>

@if (count($materials) > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($materials as $material)
                <tr>
                    <td>{{ $material->id }}</td>
                    <td>{{ $material->name }}</td>
                    <td>
                        <a href="" class="btn btn-primary">Edit</a>
                        <form action="" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No materials found.</p>
@endif 
    </div>
@endsection