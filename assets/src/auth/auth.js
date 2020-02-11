$(function () {
    function getPermissions(role)
    {
        $.ajax({
            type: "get",
            dataType: 'json',
            url: '/auth/permissions',
            data: {'role': role},
            success: function (data) {
                $('#auth-permissions').empty();
                $.each(data.permissions, function (key, value) {
                    $('#auth-permissions').append($('<option></option>').val(value.name).html(value.description));
                });
                $('#auth-permission-list').empty();
                $.each(data.permissionList, function (key, value) {
                    $('#auth-permission-list').append($('<option></option>').val(value.name).html(value.description));
                });
            },
        });
    }
    $('#auth-roles').on('change', function () {
        var role = $(this).val()[0];
        getPermissions(role);
    });
    $('#add-permissions').on('click', function () {
        var role = $('#auth-roles').val()[0];
        var permissions = $('#auth-permission-list').val();
        $.ajax({
            type: "post",
            dataType: 'json',
            url: '/auth/add?role=' + role,
            data: {'permissions': permissions},
            success: function (r) {
                getPermissions(role);
            }
        });
        getPermissions(role);
    });
    $('#delete-permissions').on('click', function () {
        var role = $('#auth-roles').val()[0];
        var permissions = $('#auth-permissions').val();
        $.ajax({
            type: "post",
            dataType: 'json',
            url: '/auth/delete?role=' + role,
            data: {'permissions': permissions},
            success: function (r) {
                getPermissions(role);
            },
        });
    });
    $('.auth-editor select').height($('body').height() - 320);
});


