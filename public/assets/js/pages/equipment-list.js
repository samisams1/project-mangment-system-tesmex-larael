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

function ProjectClientFormatter(value, row, index) {
    var clients = Array.isArray(row.clients) && row.clients.length ? row.clients : '<span class="badge bg-primary">' + label_not_assigned + '</span>';
    if (Array.isArray(clients)) {
        clients = clients.map(client => '<li>' + client + '</li>');
        // Generate HTML for the list of clients
        var clientListHtml = '<ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">' + clients.join('') + '</ul>';
        // Add edit button after the list of clients
        clientListHtml += '<a href="javascript:void(0)" class="btn btn-icon btn-outline-primary btn-sm rounded-circle edit-project update-users-clients" data-id="' + row.id + '">' +
            '<span class="bx bx-edit-alt"></span>' +
            '</a>';
        return clientListHtml;
    } else {
        return clients;
    }
}

function ProjectUserFormatter(value, row, index) {
    if (Array.isArray(row.users) && row.users.length) {
        var users = row.users;
        users = users.map(user => '<li>' + user + '</li>');
        var userListHtml = '<ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">' + users.join('') +
            '<li title="' + label_update + '">' +
            '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-outline-primary btn-sm rounded-circle edit-project update-users-clients" data-id="' + row.id + '">' +
            '<span class="bx bx-edit"></span>' +
            '</a>' +
            '</li></ul>';
        return userListHtml;
    } else {
        var notAssignedHtml = '<span class="badge bg-primary">' + label_not_assigned + '</span>';
        notAssignedHtml += '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-outline-primary btn-sm rounded-circle edit-project update-users-clients" data-id="' + row.id + '">' +
            '<span class="bx bx-edit"></span>' +
            '</a>';
        return notAssignedHtml;
    }
}

function ProjectClientFormatter(value, row, index) {
    if (Array.isArray(row.clients) && row.clients.length) {
        var clients = row.clients;
        clients = clients.map(user => '<li>' + user + '</li>');
        var clientListHtml = '<ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">' + clients.join('') +
            '<li title="' + label_update + '">' +
            '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-outline-primary btn-sm rounded-circle edit-project update-users-clients" data-id="' + row.id + '">' +
            '<span class="bx bx-edit"></span>' +
            '</a>' +
            '</li></ul>';
        return clientListHtml;
    } else {
        var notAssignedHtml = '<span class="badge bg-primary">' + label_not_assigned + '</span>';
        notAssignedHtml += '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-outline-primary btn-sm rounded-circle edit-project update-users-clients" data-id="' + row.id + '">' +
            '<span class="bx bx-edit"></span>' +
            '</a>';
        return notAssignedHtml;
    }
}


$('#status_filter,#projects_user_filter,#projects_client_filter').on('change', function (e) {
    e.preventDefault();
    $('#equipments_table').bootstrapTable('refresh');
});


function actionFormatterUsers(value, row, index) {
    return [
        '<a href="/users/edit/' + row.id + '" title=' + label_update + '>' +
        '<i class="bx bx-edit mx-1">' +
        '</i>' +
        '</a>' +
        '<button title=' + label_delete + ' type="button" class="btn delete" data-id=' + row.id + ' data-type="users">' +
        '<i class="bx bx-trash text-danger mx-1"></i>' +
        '</button>'
    ]
}

function actionFormatterClients(value, row, index) {
    return [
        '<a href="/clients/edit/' + row.id + '" title=' + label_update + '>' +
        '<i class="bx bx-edit mx-1">' +
        '</i>' +
        '</a>' +
        '<button title=' + label_delete + ' type="button" class="btn delete" data-id=' + row.id + ' data-type="clients">' +
        '<i class="bx bx-trash text-danger mx-1"></i>' +
        '</button>'
    ]
}

function userFormatter(value, row, index) {
    return '<div class="d-flex">' + row.photo + '<div class="mx-2 mt-2"><h6 class="mb-1">' + row.first_name + ' ' + row.last_name +
    (row.status === 1 ? ' <span class="badge bg-success">Active</span>' : ' <span class="badge bg-danger">Deactive</span>') +
    '</h6><p class="text-muted">' + row.email + '</p></div>' +
    '</div>';

}

function clientFormatter(value, row, index) {
    return '<div class="d-flex">' + row.profile + '<div class="mx-2 mt-2"><h6 class="mb-1">' + row.first_name + ' ' + row.last_name +
    (row.status === 1 ? ' <span class="badge bg-success">Active</span>' : ' <span class="badge bg-danger">Deactive</span>') +
    '</h6><p class="text-muted">' + row.email + '</p></div>' +
    '</div>';

}

function assignedFormatter(value, row, index) {
    return '<div class="d-flex justify-content-start align-items-center"><div class="text-center mx-4"><span class="badge rounded-pill bg-primary" >' + row.projects + '</span><div>' + label_projects + '</div></div>' +
        '<div class="text-center"><span class="badge rounded-pill bg-primary" >' + row.tasks + '</span><div>' + label_tasks + '</div></div></div>'
}

function queryParamsUsersClients(p) {
    return {
        type: $('#type').val(),
        typeId: $('#typeId').val(),
        page: p.offset / p.limit + 1,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
