'use strict';


function queryParamsEquipments(p) {
    return {
        "status": $('#status_filter').val(),
        "id": $('#projects_user_filter').val(),
        "craeted_by": $('#projects_client_filter').val(),
        page: p.offset / p.limit + 1,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}


window.icons = {
    refresh: 'bx-refresh',
    toggleOn: 'bx-toggle-right',
    toggleOff: 'bx-toggle-left'
}

function loadingTemplate(message) {
    return '<i class="bx bx-loader-alt bx-spin bx-flip-vertical" ></i>'
}

function actionsFormatter(value, row, index) {
    return [
        '<a href="javascript:void(0);" class="edit-project" data-id=' + row.id + ' title=' + label_update + '>' +
        '<i class="bx bx-edit mx-1">' +
        '</i>' +
        '</a>' +
        '<button title=' + label_delete + ' type="button" class="btn delete" data-id=' + row.id + ' data-type="projects" data-table="equipments_table">' +
        '<i class="bx bx-trash text-danger mx-1"></i>' +
        '</button>' +
        '<a href="javascript:void(0);" class="duplicate" data-table="equipments_table" data-id=' + row.id + ' data-type="projects" title=' + label_duplicate + '>' +
        '<i class="bx bx-copy text-warning mx-2"></i>' +
        '</a>'+
        '<a href="javascript:void(0);" class="quick-view" data-id=' + row.id + ' data-type="project" title="' + label_quick_view + '">' +
        '<i class="bx bx-info-circle text-primary mx-3"></i>' +
        '</a>'
    ]
}

$('#status_filter,#projects_user_filter,#projects_client_filter').on('change', function (e) {
    e.preventDefault();
    $('#equipments_table').bootstrapTable('refresh');
});





