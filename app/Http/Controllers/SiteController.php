<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        $constructionSiteData = [
            [
                'id' => 1,
                'name' => 'Main Building',
                'location' => '123 Main Street, Anytown USA',
                'status' => 'In Progress',
                'start_date' => '2023-04-01',
                'end_date' => '2024-06-30',
                'budget' => 5000000,
                'contractor' => 'ABC Construction Co.'
            ],
            [
                'id' => 2,
                'name' => 'Parking Garage',
                'location' => '456 Oak Street, Anytown USA',
                'status' => 'Planning',
                'start_date' => '2023-09-01',
                'end_date' => '2024-12-31',
                'budget' => 2500000,
                'contractor' => 'XYZ Builders'
            ],
            [
                'id' => 3,
                'name' => 'Retail Complex',
                'location' => '789 Elm Avenue, Anytown USA',
                'status' => 'In Progress',
                'start_date' => '2022-11-15',
                'end_date' => '2024-03-31',
                'budget' => 8000000,
                'contractor' => 'Acme Construction'
            ],
            [
                'id' => 4,
                'name' => 'Residential Towers',
                'location' => '321 Pine Road, Anytown USA',
                'status' => 'Completed',
                'start_date' => '2021-06-01',
                'end_date' => '2023-12-31',
                'budget' => 15000000,
                'contractor' => 'Metropolis Developers'
            ],
            [
                'id' => 5,
                'name' => 'Community Center',
                'location' => '159 Oak Lane, Anytown USA',
                'status' => 'Planning',
                'start_date' => '2024-01-01',
                'end_date' => '2025-06-30',
                'budget' => 3000000,
                'contractor' => 'Local Build Inc.'
            ]
        ];

        return view('site.index', ['constructionSiteData' => $constructionSiteData]);
    }
}