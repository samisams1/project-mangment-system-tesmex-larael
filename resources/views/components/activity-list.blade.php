<div class="tab-pane fade active show" id="navs-top-schedule" role="tabpanel">
    <!-- Filter Section -->
    <div class="mb-4">
        <form method="GET" action="/tasks/information/{{ $taskId }}" class="row g-3 align-items-end">
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Select Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>
                            {{ $status->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="priority" class="form-select form-select-sm">
                    <option value="">Select Priority</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority->id }}" {{ request('priority') == $priority->id ? 'selected' : '' }}>
                            {{ $priority->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            </div>
        </form>
    </div>

    <!-- Action Buttons -->
    <div class="mb-3 d-flex align-items-center">
        <form method="GET" action="{{ route('activities.export') }}" class="d-inline me-2">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <input type="hidden" name="priority" value="{{ request('priority') }}">
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            <button type="submit" class="btn btn-info btn-sm">
                <i class="fas fa-file-pdf"></i> PDF
            </button>
        </form>
        <form method="GET" action="{{ route('activities.exportCsv') }}" class="d-inline me-2">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <input type="hidden" name="priority" value="{{ request('priority') }}">
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            <button type="submit" class="btn btn-success btn-sm">
                <i class="fas fa-file-csv"></i> CSV
            </button>
        </form>
        <button class="btn btn-warning btn-sm" onclick="printTable()">
            <i class="fas fa-print"></i> Print
        </button>
    </div>

    <!-- Task Table -->
    <table id="task_table"
           data-toggle="table"
           data-loading-template="loadingTemplate"
           data-url="/activity/listing{{ !empty($taskId) ? '/' . $taskId : '' }}"
           data-icons-prefix="bx"
           data-icons="icons"
           data-show-refresh="true"
           data-total-field="total"
           data-trim-on-search="false"
           data-data-field="rows"
           data-page-list="[5, 10, 20, 50, 100, 200]"
           data-search="true"
           data-side-pagination="server"
           data-show-columns="true"
           data-pagination="true"
           data-sort-name="id"
           data-sort-order="desc"
           data-mobile-responsive="true"
           data-query-params="queryParamsTasks">
        <thead class="tablehead">
            <tr>
                <th data-checkbox="true"></th>
                <th data-sortable="true" data-field="id">{{ get_label('id', 'ID') }}</th>
                <th data-sortable="true" data-field="wbs">{{ get_label('wbs', 'WBS') }}</th> quantity
                <th data-sortable="true" data-field="activity_name">{{ get_label('activity_name', 'Activity Name') }}</th>
                <th data-sortable="true" data-field="unit">{{ get_label('unit', 'Unit') }}</th> 
                <th data-sortable="true" data-field="quantity">{{ get_label('Quantity', 'quantity') }}</th> 
                <th data-sortable="true" data-field="priority" class="priority-column">{{ get_label('priority', 'Priority') }}</th>
                <th data-sortable="true" data-field="start_date">{{ get_label('starts_at', 'Starts at') }}</th>
                <th data-sortable="true" data-field="end_date">{{ get_label('ends_at', 'Ends at') }}</th>
                <th data-field="duration">{{ get_label('duration', 'Duration') }}</th>
                <th data-sortable="true" data-field="progress">{{ get_label('progress', 'Progress') }}</th>
                <th data-sortable="true" data-field="status">{{ get_label('approval', 'Approval') }}</th>
                <th data-sortable="true" data-field="aproval_status">{{ get_label('status', 'Status') }}</th>
                <th data-sortable="true" data-field="created_at" data-visible="false">{{ get_label('created_at', 'Created at') }}</th>
                <th data-sortable="true" data-field="updated_at" data-visible="false">{{ get_label('updated_at', 'Updated at') }}</th>
                @if(getAuthenticatedUser()->hasVerifiedEmail() && getAuthenticatedUser()->hasRole('admin'))
                    <th data-formatter="actionFormatter">{{ get_label('actions', 'Actions') }}</th>
                @endif
            </tr>
        </thead>
    </table>
</div>

<script>
function actionFormatter(value, row, index) {
    return `
        <div class="dropdown">
            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="actionDropdown${index}" data-bs-toggle="dropdown" aria-expanded="false">
                Actions
            </button>
            <ul class="dropdown-menu" aria-labelledby="actionDropdown${index}">
                <li>
                    <a class="dropdown-item" href="/tasks/edit/${row.id}">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </li>
                <li>
                    <form method="POST" action="/tasks/delete/${row.id}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this task?')">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    `;
}
</script>