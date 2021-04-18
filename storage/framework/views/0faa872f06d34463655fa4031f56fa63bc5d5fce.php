<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card  card-outline">
                <div class="card-header bg-dark">
                    Application Permissions
                    <div class="float-right <?php echo e(Session::get('create')); ?>">
                        <button class="btn btn-outline-primary text-white" onclick="modalAddMenu()">
                            <i class="fas fa-plus"></i>&nbsp;&nbsp;New Menu
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered" id="user-permission-table" style="min-width: 100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="hidden">User ID</th>
                                <th>Fullname</th>
                                <th>Username</th>
                                <th>Departemen</th>
                                <th>Email</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th class="hidden">User ID</th>
                                <th class="search-col">Fullname</th>
                                <th ></th>
                                <th ></th>
                                <th ></th>
                                <th ></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom-plugin'); ?>
    <script>
        function format ( table_id ) {
            return '<table class="table table-hover" width="100%" id="table-data_'+table_id+'">'+
                        '<thead>'+
                        '<tr>'+
                        '<th>#</th>'+
                        '<th>Application Name</th>'+
                        '<th style="width:400px">Application Description</th>'+
                        '<th>Access</th>'+
                        '<th>#</th>'+
                        '</tr>'+
                        '</thead'+
            '</table>';
        }
        var iTableCounter=1;
        var oInnerTable;
        var table = $('#user-permission-table').DataTable({
            rowId: 'encrypt_id',
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?php echo e(url('master-apps/manage-user-permission/get-data')); ?>",
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
                    data            : 'encrypt_id',
                    name            : 'encrypt_id',
                    "className"     :'hidden',
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
                    data: 'departement_name',
                    name: 'departement_name'
                },

                {
                    data: 'email',
                    name: 'email'
                },
                {
                    "className"     :      'details-control',
                    "orderable"     :      false,
                    "data"          :      'detail',
                    "defaultContent": '<a class="btn btn-outline-primary" href="javascript:void(0);" ><i class="fas fa-eye"></i>&nbsp; Application Permission</a>'
                },
            ]
        });
        // Setup - add a text input to each footer cell
        $('#user-permission-table tfoot .search-col').each( function (i) {
            var title = $('#user-permission-table thead th').eq( $(this).index() ).text();
            $(this).html( '<input type="text" placeholder="Search '+title+'" data-index="'+$(this).index()+'" class="form-control"/>' );
        } );

        $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
            table
                .column( $(this).data('index') )
                .search( this.value )
                .draw();
        } );

        $('#user-permission-table tbody').on('click', 'td.details-control', function () {
            let tr          = $(this).closest('tr');
            let row         = table.row( tr );
            let user_id     = tr[0]['id'];


            if ( row.child.isShown() )
            {
                row.child.hide();
                tr.removeClass('shown');
            }
            else
            {
                row.child( format(user_id) ).show();
                tr.addClass('shown');
                $('#table-data_'+user_id).DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "<?php echo e(url('master-apps/manage-user-permission/get-data-application-permission')); ?>",
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
                            d.user_id= user_id;
                        }
                    },
                    columns:
                    [
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'application_name',
                            name: 'application_name'
                        },

                        {
                            data: 'application_description',
                            name: 'application_description'
                        },

                        {
                            data: 'status',
                            name: 'status'
                        },

                        {
                            data: 'action',
                            name: 'action'
                        }
                    ]
                });

                iTableCounter = iTableCounter + 1;
            }
        });

        function changeApplicationPermission(data)
        {
            if (data.checked)
            {
                is_active     = '1';
            }
            else
            {
                is_active     = '0';
            }
            data                = data.id.split('_');
            application_id      = data[2];
            user_id             = data[3];

            $.ajax({
                url:"<?php echo e(url('master-apps/manage-user-permission/change-application-permission')); ?>",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    'application_id'            : application_id,
                    'user_id'                   : user_id,
                    'is_active'               : is_active
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
                            $('#table-data_'+user_id).DataTable().ajax.reload(null,false);
                        break;

                        case '01':
                            Swal.fire({
                                title: 'Process Error ! ',
                                text: data.message,
                                icon: 'error'
                            });
                            $('#table-data_'+user_id).DataTable().ajax.reload(null,false);
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
        function menuPermissionModal(data)
        {
            data                = data.id.split('_');
            application_id      = data[2];
            user_id             = data[3];
            application_name    = data[4];

            $.ajax({
                url:"<?php echo e(url('master-apps/manage-user-permission/menu-permission-modal')); ?>",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:
                {
                    'user_id' : user_id,
                    'application_id' : application_id,
                },
                success: function(data)
                {
                    switch (data.status)
                    {
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
                        default:
                            $('#modal').modal('show');
                            $('#modal-title').html('Manage Menu Permission User on '+application_name);
                            $('#modal-size').addClass('modal-xl');
                            $('#modal .modal-body').html(data);
                        break;
                    }
                },
                complete: function (data) {
                    $('.loading-bar').addClass('hidden');
                }
            });
        }

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/sentul-integrated-system-stable/resources/views/master_app/user_permissions/index.blade.php ENDPATH**/ ?>