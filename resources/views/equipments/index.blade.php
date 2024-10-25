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

    <div class="nav-align-top my-4">
        <!-- Add Equipment Button -->
        <div class="mb-3">
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_Equipment_modal">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="{{ get_label('create_equipment', 'Add Equipment') }}">
                    <i class="bx bx-plus"></i> {{ get_label('add_equipment', 'Add Equipment') }}
                </button>
            </a>
        </div>

        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-equipments" aria-controls="navs-top-equipments" aria-selected="true">
                    <i class="menu-icon tf-icons bx bx-wrench text-warning"></i>{{ get_label('equipment', 'Equipment') }}
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-requests" aria-controls="navs-top-requests" aria-selected="false">
                    <i class="menu-icon tf-icons bx bx-paper-plane text-success"></i>{{ get_label('requests', 'Requests') }}
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-schedule" aria-controls="navs-top-schedule" aria-selected="false">
                    <i class="menu-icon tf-icons bx bx-calendar text-primary"></i>{{ get_label('schedule', 'Schedule') }}
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-shift-management" aria-controls="navs-top-shift-management" aria-selected="false">
                    <i class="menu-icon tf-icons bx bx-time text-warning"></i>{{ get_label('shift_management', 'Shift Management') }}
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-vouchers" aria-controls="navs-top-vouchers" aria-selected="false">
                    <i class="menu-icon tf-icons bx bx-money text-danger"></i>{{ get_label('vouchers', 'Vouchers') }}
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade active show" id="navs-top-equipments" role="tabpanel">
                <x-equipments-card :equipments="$equipments" :warehouses="$warehouses" :units="$units" />
            </div>
            <div class="tab-pane fade" id="navs-top-requests" role="tabpanel">
                <h4 class="fw-bold">{{ get_label('requests', 'Equipment Requests') }}</h4>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ get_label('requester', 'Requester') }}</th>
                            <th>{{ get_label('equipment', 'Equipment') }}</th>
                            <th>{{ get_label('status', 'Status') }}</th>
                            <th>{{ get_label('date', 'Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Alemayehu Tades</td>
                            <td>Excavator</td>
                            <td>Approved</td>
                            <td>2023-10-20</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Yosissable Lemenh</td>
                            <td>Forklift</td>
                            <td>Pending</td>
                            <td>2023-10-22</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="navs-top-schedule" role="tabpanel">
                <h4 class="fw-bold">{{ get_label('schedule', 'Equipment Schedule') }}</h4>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ get_label('equipment', 'Equipment') }}</th>
                            <th>{{ get_label('assigned_to', 'Assigned To') }}</th>
                            <th>{{ get_label('date', 'Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Bulldozer</td>
                            <td>Alemayehu Tades</td>
                            <td>2023-10-23</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Crane</td>
                            <td>Yosissable Lemenh</td>
                            <td>2023-10-24</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="navs-top-shift-management" role="tabpanel">
                <h4 class="fw-bold">{{ get_label('shift_management', 'Equipment Shift Management') }}</h4>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ get_label('equipment', 'Equipment') }}</th>
                            <th>{{ get_label('shift_name', 'Shift Name') }}</th>
                            <th>{{ get_label('operator', 'Operator') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Loader</td>
                            <td>Day Shift</td>
                            <td>Alemayehu Tades</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Dump Truck</td>
                            <td>Night Shift</td>
                            <td>Yosissable Lemenh</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="navs-top-vouchers" role="tabpanel">
                <h4 class="fw-bold">{{ get_label('vouchers', 'Equipment Vouchers') }}</h4>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ get_label('voucher_code', 'Voucher Code') }}</th>
                            <th>{{ get_label('equipment', 'Equipment') }}</th>
                            <th>{{ get_label('amount', 'Amount') }}</th>
                            <th>{{ get_label('expiry_date', 'Expiry Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>VOUCHER123</td>
                            <td>Generator</td>
                            <td>$50.00</td>
                            <td>2023-12-31</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>VOUCHER456</td>
                            <td>Concrete Mixer</td>
                            <td>$100.00</td>
                            <td>2024-01-15</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Equipment Modal -->
<div class="modal fade" id="create_Equipment_modal" tabindex="-1" aria-labelledby="createEquipmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createEquipmentModalLabel">{{ get_label('create_equipment', 'Add Equipment') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for creating equipment -->
                <form id="createEquipmentForm">
                    <div class="mb-3">
                        <label for="equipmentName" class="form-label">{{ get_label('equipment_name', 'Equipment Name') }}</label>
                        <input type="text" class="form-control" id="equipmentName" required>
                    </div>
                    <div class="mb-3">
                        <label for="equipmentType" class="form-label">{{ get_label('equipment_type', 'Equipment Type') }}</label>
                        <select class="form-select" id="equipmentType" required>
                            <option value="" disabled selected>{{ get_label('select_type', 'Select Equipment Type') }}</option>
                            @foreach($equipmentTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="equipmentModel" class="form-label">{{ get_label('model', 'Model') }}</label>
                        <input type="text" class="form-control" id="equipmentModel" required>
                    </div>
                    <div class="mb-3">
                        <label for="equipmentSerial" class="form-label">{{ get_label('serial_number', 'Serial Number') }}</label>
                        <input type="text" class="form-control" id="equipmentSerial" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ get_label('close', 'Close') }}</button>
                <button type="button" class="btn btn-primary" onclick="submitEquipmentForm()">{{ get_label('create', 'Create') }}</button>
            </div>
        </div>
    </div>
</div>

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

    function submitEquipmentForm() {
        // Implement form submission logic here
        const form = document.getElementById('createEquipmentForm');
        if (form.checkValidity()) {
            // Add your form submission logic (e.g., AJAX request)
            alert('Equipment created successfully!'); // Placeholder
            // Close the modal after submission
            const modal = bootstrap.Modal.getInstance(document.getElementById('create_Equipment_modal'));
            modal.hide();
        } else {
            form.reportValidity();
        }
    }
</script>
@endsection