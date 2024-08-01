@if (is_countable($equipments) && count($equipments) > 0)
<div class="card">
    <div class="card-body">
        {{ $slot }}
        <div class="row">
            @if(isAdminOrHasAllDataAccess())
            <div class="col-md-4 mb-3">
                <!-- Button to trigger modal -->
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newEquipmentModal">New Equipment</a>
            </div>
            @endif
        </div>

        <!-- New Equipment Modal -->
        <div class="modal fade" id="newEquipmentModal" tabindex="-1" aria-labelledby="newEquipmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newEquipmentModalLabel">Create Equipment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="equipmentForm" action="{{ route('equipments.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="item" class="form-label">ITEM<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="item" id="item" required>
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">QUANTITY<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="quantity" id="quantity" required>
                            </div>
                            <div class="mb-3">
                                <label for="rate_with_vat" class="form-label">RATE WITH VAT<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="rate_with_vat" id="rate_with_vat" required>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">AMOUNT<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="amount" id="amount" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">STATUS<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="status" id="status" required>
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">TYPE<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="type" id="type" required>
                            </div>
                            <div class="mb-3">
                                <label for="reorder_quantity" class="form-label">REORDER QUANTITY<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="reorder_quantity" id="reorder_quantity" required>
                            </div>
                            <div class="mb-3">
                                <label for="min_quantity" class="form-label">MIN QUANTITY<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="min_quantity" id="min_quantity" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="unit_id" class="form-label">UNIT ID<span class="text-danger">*</span></label>
                                <select class="form-control" name="unit_id" id="unit_id" required>
                                    <option value="">Select a Unit</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="warehouse_id" class="form-label">WAREHOUSE ID<span class="text-danger">*</span></label>
                                <select class="form-control" name="warehouse_id" id="warehouse_id" required>
                                    <option value="">Select a warehouse</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="remark" class="form-label">REMARK</label>
                        <textarea class="form-control" name="remark" id="remark" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" form="equipmentForm">Save</button>
            </div>
        </div>
    </div>
</div>


        <div class="table-responsive text-nowrap">
            <input type="hidden" id="data_type" value="equipments">
            <table id="equipments_table"
                data-toggle="table"
                data-loading-template="loadingTemplate"
                data-icons-prefix="bx"
                data-icons="icons"
                data-show-refresh="true"
                data-total-field="total"
                data-trim-on-search="true"
                data-data-field="rows"
                data-page-list="[5, 10, 20, 50, 100, 200]"
                data-search="true"
                data-side-pagination="server"
                data-show-columns="true"
                data-pagination="true"
                data-sort-name="id"
                data-sort-order="desc"
                data-mobile-responsive="true"
                data-query-params="queryParamsEquipments">
                <thead>
                    <tr>
                        <th>{{ get_label('id', 'Id') }}</th>
                        <th>{{ get_label('item', 'Item') }}</th>
                        <th>{{ get_label('quantity', 'Quantity') }}</th>
                        <th>{{ get_label('rate_with_vat', 'Rate with VAT') }}</th>
                        <th>{{ get_label('amount', 'Amount') }}</th>
                        <th>{{ get_label('remark', 'Remark') }}</th>
                        <th>{{ get_label('status', 'Status') }}</th>
                        <th>{{ get_label('type', 'Type') }}</th>
                        <th>{{ get_label('reorder_quantity', 'Reorder Quantity') }}</th>
                        <th>{{ get_label('min_quantity', 'Min Quantity') }}</th>
                        <th>{{ get_label('created_by', 'Created By') }}</th>
                        <th>{{ get_label('actions', 'Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($equipments as $equipment)
                    <tr>
                        <td>{{ $equipment->id }}</td>
                        <td>{{ $equipment->item }}</td>
                        <td><span style="background-color: {{ $equipment->quantity < $equipment->min_quantity ? 'red' : 'transparent' }}; color:'white'; width: 100px; display: inline-block;">{{ $equipment->quantity }}</span></td>
                        <td>{{ $equipment->rate_with_vat }}</td>
                        <td>{{ $equipment->amount }}</td>
                        <td>{{ $equipment->remark }}</td>
                        <td>{{ $equipment->status }}</td>
                        <td>{{ $equipment->type }}</td>
                        <td>{{ $equipment->reorder_quantity }}</td>
                        <td>{{ $equipment->min_quantity }}</td>
                        <td>{{ $equipment->createdBy->first_name }}</td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('equipments.edit', $equipment->id) }}" class="btn btn-primary btn-sm mr-2">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('equipments.destroy', $equipment->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<?php $type = 'equipments'; ?>
<x-empty-state-card :type="$type" />
@endif

<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
</script>
<script src="{{ asset('assets/js/pages/equipment-list.js') }}"></script>