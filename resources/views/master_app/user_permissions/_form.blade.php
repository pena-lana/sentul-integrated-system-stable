<input type="hidden" name="user_id" id="user_id" value="{{$data['user_id']}}">
<input type="hidden" name="application_id" id="application_id" value="{{$data['application_id']}}">
<table class="table table-striped table-bordered" id="menu-permission-table" style="min-width: 100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Menu Name</th>
            <th>View</th>
            <th>Create</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th class="search-col">Application Name</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>
<script>
    var menu_permission_table = $('#menu-permission-table').DataTable({
        rowId: 'menu_id',
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('master-apps/manage-user-permission/get-menu-permission') }}",
            dataSrc: function ( json )
            {
                if(json.status!=='01' && json.status!='02') //!=false or !=token expire or !=token not valid
                {
                    return json.data;
                }
                else
                {
                    Swal.fire({
                        title: 'Process Error !',
                        text: data.message,
                        icon: 'error'
                    });
                    setTimeout(function(){ document.location.href='' }, 3000);
                }
            },
            data: function ( d ) {
                d.user_id           = $('#user_id').val();
                d.application_id    = $('#application_id').val();
            }
        },
        columns:
        [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'menu_name',
                name: 'menu_name'
            },

            {
                data: 'view',
                name: 'view'
            },

            {
                data: 'create',
                name: 'create'
            },

            {
                data: 'edit',
                name: 'edit'
            },

            {
                data: 'delete',
                name: 'delete'
            }
        ]
    });
    // Setup - add a text input to each footer cell
    $('#menu-permission-table tfoot .search-col').each( function (i) {
        var title = $('#menu-permission-table thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" data-index="'+$(this).index()+'" class="form-control"/>' );
    } );

    $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
        table
            .column( $(this).data('index') )
            .search( this.value )
            .draw();
    } );
</script>

<script>
    function changeViewPermission(data)
    {
        if (data.checked)
        {
            is_active     = '1';
        }
        else
        {
            is_active     = '0';
        }
        data            = data.id.split('_');
        menu_id         = data[2];
        user_id         = data[3];
        $.ajax({
            url:"{{url('master-apps/manage-user-permission/change-view-menu-permission')}}",
            type:'POST',
            beforeSend:function()
            {
                $('.loading-bar').removeClass('hidden');
            },
            headers: {
                'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
            },
            data:{
                'menu_id'            : menu_id,
                'user_id'            : user_id,
                'is_active'          : is_active
            },
            success: function(data)
            {
                switch (data.status)
                {
                    case '00':
                        Swal.fire({
                            title: 'Process Success ! ',
                            text: data.message,
                            icon: 'success'
                        });
                        menu_permission_table.ajax.reload(null,false);
                    break;

                    case '01':
                        Swal.fire({
                            title: 'Process Error ! ',
                            text: data.message,
                            icon: 'error'
                        });
                        menu_permission_table.ajax.reload(null,false);
                    break;

                    case '02':
                        Swal.fire({
                            title: 'Process Error !',
                            text: data.message,
                            icon: 'error'
                        });
                        setTimeout(function(){ document.location.href='' }, 3000);
                    break;
                }
            },
            complete: function (data) {
                $('.loading-bar').addClass('hidden');
            }
        });
    }
    function changeCreatePermission(data)
    {
        if (data.checked)
        {
            is_active     = '1';
        }
        else
        {
            is_active     = '0';
        }
        data            = data.id.split('_');
        menu_id         = data[2];
        user_id         = data[3];
        $.ajax({
            url:"{{url('master-apps/manage-user-permission/change-create-menu-permission')}}",
            type:'POST',
            beforeSend:function()
            {
                $('.loading-bar').removeClass('hidden');
            },
            headers: {
                'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
            },
            data:{
                'menu_id'            : menu_id,
                'user_id'            : user_id,
                'is_active'          : is_active
            },
            success: function(data)
            {
                switch (data.status)
                {
                    case '00':
                        Swal.fire({
                            title: 'Process Success ! ',
                            text: data.message,
                            icon: 'success'
                        });
                        menu_permission_table.ajax.reload(null,false);
                    break;

                    case '01':
                        Swal.fire({
                            title: 'Process Error ! ',
                            text: data.message,
                            icon: 'error'
                        });
                        menu_permission_table.ajax.reload(null,false);
                    break;

                    case '02':
                        Swal.fire({
                            title: 'Process Error !',
                            text: data.message,
                            icon: 'error'
                        });
                        setTimeout(function(){ document.location.href='' }, 3000);
                    break;
                }
            },
            complete: function (data) {
                $('.loading-bar').addClass('hidden');
            }
        });
    }
    function changeEditPermission(data)
    {
        if (data.checked)
        {
            is_active     = '1';
        }
        else
        {
            is_active     = '0';
        }
        data            = data.id.split('_');
        menu_id         = data[2];
        user_id         = data[3];
        $.ajax({
            url:"{{url('master-apps/manage-user-permission/change-edit-menu-permission')}}",
            type:'POST',
            beforeSend:function()
            {
                $('.loading-bar').removeClass('hidden');
            },
            headers: {
                'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
            },
            data:{
                'menu_id'            : menu_id,
                'user_id'            : user_id,
                'is_active'          : is_active
            },
            success: function(data)
            {
                switch (data.status)
                {
                    case '00':
                        Swal.fire({
                            title: 'Process Success ! ',
                            text: data.message,
                            icon: 'success'
                        });
                        menu_permission_table.ajax.reload(null,false);
                    break;

                    case '01':
                        Swal.fire({
                            title: 'Process Error ! ',
                            text: data.message,
                            icon: 'error'
                        });
                        menu_permission_table.ajax.reload(null,false);
                    break;

                    case '02':
                        Swal.fire({
                            title: 'Process Error !',
                            text: data.message,
                            icon: 'error'
                        });
                        setTimeout(function(){ document.location.href='' }, 3000);
                    break;
                }
            },
            complete: function (data) {
                $('.loading-bar').addClass('hidden');
            }
        });
    }
    function changeDeletePermission(data)
    {
        if (data.checked)
        {
            is_active     = '1';
        }
        else
        {
            is_active     = '0';
        }
        data            = data.id.split('_');
        menu_id         = data[2];
        user_id         = data[3];
        $.ajax({
            url:"{{url('master-apps/manage-user-permission/change-delete-menu-permission')}}",
            type:'POST',
            beforeSend:function()
            {
                $('.loading-bar').removeClass('hidden');
            },
            headers: {
                'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
            },
            data:{
                'menu_id'            : menu_id,
                'user_id'            : user_id,
                'is_active'          : is_active
            },
            success: function(data)
            {
                switch (data.status)
                {
                    case '00':
                        Swal.fire({
                            title: 'Process Success ! ',
                            text: data.message,
                            icon: 'success'
                        });
                        menu_permission_table.ajax.reload(null,false);
                    break;

                    case '01':
                        Swal.fire({
                            title: 'Process Error ! ',
                            text: data.message,
                            icon: 'error'
                        });
                        menu_permission_table.ajax.reload(null,false);
                    break;

                    case '02':
                        Swal.fire({
                            title: 'Process Error !',
                            text: data.message,
                            icon: 'error'
                        });
                        setTimeout(function(){ document.location.href='' }, 3000);
                    break;
                }
            },
            complete: function (data) {
                $('.loading-bar').addClass('hidden');
            }
        });
    }
</script>
