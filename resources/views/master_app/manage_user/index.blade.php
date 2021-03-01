@extends('layouts.app')
@section('content')

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card  card-outline">
                <div class="card-header bg-dark">
                    User Data
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered" id="user-table" style="min-width: 100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fullname</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Departemen</th>
                                <th>Status</th>
                                <th style="width: 80px">#</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th class="search-col">Fullname</th>
                                <th ></th>
                                <th class="search-col">Email</th>
                                <th class="search-col"></th>
                                <th class="search-col"></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- filter select2 --}}
    <select name="select1" id="select1" class="hidden">
        <option value="FQC">FQC</option>
        <option value="FSA">FSA</option>
        <option value="FEC">FEC</option>
        <option value="FRC">FRC</option>
    </select>
@endsection

@section('custom-plugin')
<script>
     var table = $('#user-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('master-apps/manage-user/get-data') }}",
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
			}
        },
        columns:
        [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },

            {
                data: 'fullname',
                name: 'fullname'
            },

            {
                data: 'username',
                name: 'username'
            },

            {
                data: 'email',
                name: 'email'
            },

            {
                data: 'departement',
                name: 'departement'
            },

            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'action',
                name: 'action',
                orderable: true,
                searchable: true
            },
        ],
        initComplete: function () {
            this.api().columns().every( function (i)
            {
                if (i==4)
                {
                    var column = this;
                    var select = $('<select id="select2" class="form-control select2"><option value=""></option></select>')
                        .appendTo( $(column.footer()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search( val ? '^'+val+'$' : '', true, false )
                                .draw();
                        } );


                }
            } );
        }
    });
    // Setup - add a text input to each footer cell
    $('#user-table tfoot .search-col').each( function (i) {
        var title = $('#user-table thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" data-index="'+$(this).index()+'" class="form-control"/>' );
    } );

    $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
        table
            .column( $(this).data('index') )
            .search( this.value )
            .draw();
    } );

    function editUser(data)
    {
        data        = data.id.split('_');
        user_id     = data[2];
        $.ajax({
            url:"{{url('master-apps/manage-user/edit-user')}}",
            type:'POST',
            beforeSend:function()
            {
                $('.loading-bar').removeClass('hidden');
            },
            headers: {
                'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
            },
            data:{
                'user_id'       : user_id
            },
            success: function(data)
            {
                switch (data.status)
                {
                    default:
                        $('#modal').modal('show');
                        $('#modal-title').html('Manage User');
                        $('#modal-size').addClass('modal-lg');
                        $('#modal .modal-body').html(data);
                    break;

                    case '01':
                        Swal.fire({
                            title: 'Process Error ! ',
                            text: data.message,
                            icon: 'error'
                        });
                        table.ajax.reload(null,false);
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

    function updateUser()
    {
        data    = $('#edit-user-form').serialize();
        $.ajax({
            url:"{{url('master-apps/manage-user/update-user')}}",
            type:'POST',
            beforeSend:function()
            {
                $('.loading-bar').removeClass('hidden');
            },
            headers: {
                'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
            },
            data:data,
            success: function(data)
            {
                switch (data.status)
                {
                    case '00':
                        Swal.fire({
                            title: 'Process Success  ! ',
                            text: data.message,
                            icon: 'success'
                        });
                        $('#modal').modal('hide');
                        resetModalSize();
                        table.ajax.reload(null,false);
                    break;

                    case '01':
                        Swal.fire({
                            title: 'Process Error ! ',
                            text: data.message,
                            icon: 'error'
                        });
                        $('#modal').modal('hide');
                        resetModalSize();
                        table.ajax.reload(null,false);
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

    function changeStatus(data)
    {
        if (data.checked)
        {
            status_user     = '1';
        }
        else
        {
            status_user     = '0';
        }
        data        = data.id.split('_');
        user_id     = data[2];

        $.ajax({
            url:"{{url('master-apps/manage-user/change-status-user')}}",
            type:'POST',
            beforeSend:function()
            {
                $('.loading-bar').removeClass('hidden');
            },
            headers: {
                'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
            },
            data:{
                'user_id'           : user_id,
                'status_user'       : status_user
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
                        table.ajax.reload(null,false);
                    break;

                    case '01':
                        Swal.fire({
                            title: 'Process Error ! ',
                            text: data.message,
                            icon: 'error'
                        });
                        table.ajax.reload(null,false);
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
    function verifyUser(data)
    {
        data        = data.id.split('_');
        user_id     = data[2];
        $.ajax({
            url:"{{url('master-apps/manage-user/verify-user')}}",
            type:'POST',
            beforeSend:function()
            {
                $('.loading-bar').removeClass('hidden');
            },
            headers: {
                'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
            },
            data:{
                'user_id'       : user_id
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
                        table.ajax.reload(null,false);
                    break;

                    case '01':
                        Swal.fire({
                            title: 'Process Error ! ',
                            text: data.message,
                            icon: 'error'
                        });
                        table.ajax.reload(null,false);
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

    function resetPassword(data)
    {
        data        = data.id.split('_');
        user_id     = data[2];
        $.ajax({
            url:"{{url('master-apps/manage-user/reset-password')}}",
            type:'POST',
            beforeSend:function()
            {
                $('.loading-bar').removeClass('hidden');
            },
            headers: {
                'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
            },
            data:{
                'user_id'       : user_id
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
                        table.ajax.reload(null,false);
                    break;

                    case '01':
                        Swal.fire({
                            title: 'Process Error ! ',
                            text: data.message,
                            icon: 'error'
                        });
                        table.ajax.reload(null,false);
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

    var $options = $("#select1 > option").clone();
    setTimeout(function(){  $('#select2').append($options); }, 3000);



</script>
@endsection
