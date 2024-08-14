@extends('layout')  

@section('title')  
    {{ get_label('dashboard', 'Dashboard') }}  
@endsection  

@section('content')  
    <style>  
        .custom-card {  
            background-color: #696cff; /* Primary color */  
            border-radius: 0.5rem;  
            position: relative;  
            overflow: hidden;  
        }  

        .custom-card .card-body {  
            padding: 1.5rem; /* Increased padding for spacious feel */  
        }  

        .custom-card-title {  
            font-size: 2rem; /* Larger title font */  
            color: white; /* White title color */  
        }  

        .custom-footer {  
            color: white; /* White color for footer label */  
        }  

        .custom-footer-value {  
            color: #ffc107; /* Bootstrap warning color for footer values */  
            font-weight: bold;  
        }  

        .card-header-custom {  
            background-color: #343a40; /* Darker header */  
            color: white; /* White header text */  
        }  

        .text-highlight {  
            color: #ffc107; /* Bright highlight yellow for accent */  
        }  
    </style>  
    
    <div class="container-fluid">  
        <div class="row mt-4">  
            @foreach($cards as $card)  
                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">  
                    <div class="card custom-card shadow-lg rounded">  
                        <div class="card-body">  
                            <div class="d-flex align-items-center justify-content-between">  
                                <div class="avatar flex-shrink-0">  
                                    <i class="menu-icon tf-icons bx {{ $card['icon'] }} bx-lg text-white"></i>  
                                </div>  
                                <div class="ms-3">  
                                    <h3 class="custom-card-title mb-1 fw-bold">{{ $card['title'] }}</h3>  
                                    <span class="fw-normal d-block mb-2 text-white">{{ $card['subtitle'] }}</span>  
                                </div>  
                            </div>  
                            <div class="mt-3">  
                                <span class="custom-footer fw-semibold d-block mb-1">{{ $card['footerLabel'] }}</span>  
                                @foreach($card['footerValues'] as $value)  
                                    <h4 class="custom-footer-value mb-1">{{ $value }}</h4>  
                                @endforeach  
                            </div>  
                        </div>  
                    </div>  
                </div>  
            @endforeach  
        </div>  

        <div class="row mt-4">  
            <div class="col-lg-12 col-md-12">  
                <div class="card">  
                    <div class="card-header card-header-custom">  
                        <h5 class="card-title mb-0 fw-bold">{{ get_label('Employee Statistics', 'Employee Statistics') }}</h5>  
                    </div>  
                    <div class="card-body">  
                        <div class="row">  
                            @foreach($statCards as $statCard)  
                                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">  
                                    <div class="card custom-card shadow-lg rounded">  
                                        <div class="card-body">  
                                            <h3 class="custom-card-title mb-1 fw-bold">{{ $statCard['title'] }}</h3>  
                                            <span class="fw-normal d-block mb-2 text-white">{{ $statCard['subtitle'] }}</span>  
                                            <div class="mt-3">  
                                                @foreach($statCard['footer'] as $footerLabel => $footerValue)  
                                                    <div class="d-flex align-items-center justify-content-between mt-2">   
                                                        <span class="fw-semibold text-white">{{ $footerLabel }}</span>  
                                                        <h4 class="text-highlight mb-0">{{ $footerValue }}</h4>  
                                                    </div>  
                                                @endforeach  
                                            </div>  
                                        </div>  
                                    </div>  
                                </div>  
                            @endforeach  
                        </div>  
                    </div>  
                </div>  
            </div>  
        </div>  
    </div>  
@endsection