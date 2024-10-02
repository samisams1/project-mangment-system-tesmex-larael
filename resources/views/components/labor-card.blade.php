<!-- meetings -->

<div class="card">
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            {{$slot}}
 
            <input type="hidden" id="data_type" value="labor">
            <table id="table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/laborPossition/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                <thead>
                    <tr>
                        <th data-checkbox="true"></th>
                        <th data-sortable="true" data-field="id"><?= get_label('id', 'ID') ?></th>
                        <th data-sortable="true" data-field="name"><?= get_label('name', 'Full Name') ?></th>
                        <th data-sortable="true" data-field="position"><?= get_label('position', 'Position') ?></th>
                        <th data-sortable="true" data-field="hourly_rate"><?= get_label('hourly_rate', 'Hourly Rate') ?></th>
                        <th data-sortable="true" data-field="availability"><?= get_label('availability', 'Hourly Rate') ?></th>
                        <th data-sortable="true" data-field="status"><?= get_label('status', 'Status') ?></th>
                    </tr>
                </thead>
            </table>
      
        </div>
    </div>
</div>