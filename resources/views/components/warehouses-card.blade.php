<!-- resources/views/components/warehouses-card.blade.php -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createWarehouseModal">
        <?= get_label('create_warehouse', 'Create Warehouse') ?>
    </button>
<table id="warehouses_table"
       data-toggle="table"
       data-loading-template="loadingTemplate"
       data-url="{{ route('warehouses.data') }}"
       data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true"
       data-query-params="queryParamsWarehouses">
       
    <thead>
        <tr>
        <th data-sortable="true" data-field="id"><?= get_label('id', 'ID') ?></th>
        <th data-sortable="true" data-field="name"><?= get_label('name', 'Name') ?></th>
        <th data-sortable="true" data-field="location"><?= get_label('location', 'Location') ?></th>
        <th data-sortable="true" data-field="contact_info"><?= get_label('contact_info', 'Contact') ?></th>
         <th data-field="created_by" data-formatter="ProjectUserFormatter">Created By</th>
        <th data-sortable="true" data-field="created_at" data-visible="false"><?= get_label('created_at', 'Created at') ?></th>
        <th data-sortable="true" data-field="updated_at" data-visible="false"><?= get_label('updated_at', 'Updated at') ?></th>
        <th data-formatter="actionsFormatter"><?= get_label('actions', 'Actions') ?></th>
        </tr>
    </thead>
</table>
<div class="modal fade" id="createWarehouseModal" tabindex="-1" aria-labelledby="createWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createWarehouseModalLabel"><?= get_label('create_warehouse', 'Create Warehouse') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
    <div class="mb-3">
        <label for="warehouseName" class="form-label"><?= get_label('name', 'Name') ?></label>
        <input type="text" class="form-control" id="warehouseName" placeholder="<?= get_label('name', 'Name') ?>">
    </div>
    <div class="mb-3">
        <label for="warehouseDescription" class="form-label"><?= get_label('description', 'Description') ?></label>
        <textarea class="form-control" id="warehouseDescription" rows="3" placeholder="<?= get_label('description', 'Description') ?>"></textarea>
    </div>
    <div class="mb-3">
        <label for="warehouseLocation" class="form-label"><?= get_label('location', 'Location') ?></label>
        <input type="text" class="form-control" id="warehouseLocation" placeholder="<?= get_label('location', 'Location') ?>">
    </div>
    <div class="mb-3">
        <label for="warehouseManager" class="form-label"><?= get_label('manager', 'Manager') ?></label>
        <input type="text" class="form-control" id="warehouseManager" placeholder="<?= get_label('manager', 'Manager') ?>">
    </div>
    <div class="mb-3">
        <label for="warehouseContactInfo" class="form-label"><?= get_label('contact_info', 'Contact Information') ?></label>
        <input type="text" class="form-control" id="warehouseContactInfo" placeholder="<?= get_label('contact_info', 'Contact Information') ?>">
    </div>
    <div class="mb-3">
        <label for="warehouseCreatedBy" class="form-label"><?= get_label('created_by', 'Created By') ?></label>
        <input type="text" class="form-control" id="warehouseCreatedBy" placeholder="<?= get_label('created_by', 'Created By') ?>">
    </div>
</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= get_label('cancel', 'Cancel') ?></button>
                <button type="button" class="btn btn-primary" id="saveWarehouse"><?= get_label('save', 'Save') ?></button>
            </div>
        </div>
    </div>
</div>
<script>
    function actionFormatter(value, row, index) {
        return `
            <a href="#" class="btn btn-primary btn-sm mr-2">Edit</a>
            <a href="#" class="btn btn-danger btn-sm">Delete</a>
        `;
    }

    function loadingTemplate() {
        return '<div class="spinner-border text-primary"></div>';
    }
</script>

<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
</script>
<script src="{{asset('assets/js/pages/warehouses.js')}}"></script>