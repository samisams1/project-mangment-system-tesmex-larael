<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequestsController extends Controller
{
    public function index()
    {
        // Static data for demonstration
        $requests = [
            [
                'id' => 1,
                'title' => 'Sample Request 1',
                'description' => 'This is the first sample request.',
                'created_at' => '2024-05-08 10:00:00',
            ],
            [
                'id' => 2,
                'title' => 'Sample Request 2',
                'description' => 'This is the second sample request.',
                'created_at' => '2024-05-09 14:30:00',
            ],
            // Add more request data as needed
        ];

        return view('requests.index', compact('requests'));
    }

    public function show($id)
    {
        // Static data for demonstration
        $request = [
            'id' => $id,
            'title' => 'Sample Request',
            'description' => 'This is a sample request.',
            'created_at' => '2024-05-08 10:00:00',
        ];

        return view('requests.show', compact('request'));
    }

    public function create()
    {
        return view('requests.create');
    }

    public function store(Request $request)
    {
        // Process and store the request data

        return redirect()->route('requests.index')->with('success', 'Request created successfully!');
    }
}