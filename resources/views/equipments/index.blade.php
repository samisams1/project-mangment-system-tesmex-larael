@extends('layout')

@section('title')
    {{ get_label('equipments', 'Equipments') }} - {{ get_label('list_view', 'List View') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-2 mt-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ url('/equipments') }}">{{ get_label('equipments', 'Equipments') }}</a>
                        </li>
                    </ol>
                </nav>
                @if (session('success'))  
        <div class="alert alert-success notification" id="success-message">  
            {{ session('success') }}  
        </div>  
    @endif  

    @if (session('error'))  
        <div class="alert alert-danger notification" id="error-message">  
            {{ session('error') }}  
        </div>  
    @endif  
            </div>
        </div>

       <x-equipments-card :equipments="$equipments" :warehouses="$warehouses" :units="$units" />

        
    </div>
@endsection
<style>
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        padding: 10px 20px;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        opacity: 1;
        transition: opacity 0.3s ease-in-out;
    }

    .notification.fade-out {
        opacity: 0;
    }
</style>

<script>  
        // Hide success message after 3 seconds  
        setTimeout(() => {  
            const successMessage = document.getElementById('success-message');  
            if (successMessage) {  
                successMessage.style.display = 'none';  
            }  
        }, 3000);  

        // Hide error message after 3 seconds  
        setTimeout(() => {  
            const errorMessage = document.getElementById('error-message');  
            if (errorMessage) {  
                errorMessage.style.display = 'none';  
            }  
        }, 3000);  
    </script>