<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card  card-outline">
                <div class="card-header bg-dark">
                    Application Data
                    <div class="float-right <?php echo e(Session::get('create')); ?>">
                        <button class="btn btn-outline-primary text-white" onclick="modalAddMenu()">
                            <i class="fas fa-plus"></i>&nbsp;&nbsp;New Menu
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered" id="menu-table" style="min-width: 100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="hidden">cust id</th>
                                <th>Application Name</th>
                                <th>Menu Name</th>
                                <th>Menu Route</th>
                                <th>Menu Position</th>
                                <th>Status</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th class="hidden"></th>
                                <th class="search-col">Application Name</th>
                                <th ></th>
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
            return '<table class="table table-hover" width="100%" id="table-data-'+table_id+'">'+
                        '<thead>'+
                        '<tr>'+
                        '<th>#</th>'+
                        '<th>Menu Name</th>'+
                        '<th>Menu Route</th>'+
                        '<th>Menu Position</th>'+
                        '<th>Status</th>'+
                        '<th>#</th>'+
                        '</tr>'+
                        '</thead'+
            '</table>';
        }
        var iTableCounter=1;
        var oInnerTable;
        var table = $('#menu-table').DataTable({
            rowId: 'menu_id',
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?php echo e(url('master-apps/manage-menu/get-data')); ?>",
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
                    data            : 'menu_id',
                    name            : 'menu_id',
                    "className"     :'hidden',

                },
                {
                    data: 'application_name',
                    name: 'application_name'
                },

                {
                    data: 'menu_name',
                    name: 'menu_name'
                },

                {
                    data: 'menu_route',
                    name: 'menu_route'
                },

                {
                    data: 'menu_position',
                    name: 'menu_position'
                },

                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                },
            ]
        });
        // Setup - add a text input to each footer cell
        $('#menu-table tfoot .search-col').each( function (i) {
            var title = $('#menu-table thead th').eq( $(this).index() ).text();
            $(this).html( '<input type="text" placeholder="Search '+title+'" data-index="'+$(this).index()+'" class="form-control"/>' );
        } );

        $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
            table
                .column( $(this).data('index') )
                .search( this.value )
                .draw();
        } );

        function modalAddMenu()
        {
            $.ajax({
                url:"<?php echo e(url('master-apps/manage-menu/add-new-menu-modal')); ?>",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:'new',
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
                            $('#modal-title').html('Manage Application');
                            $('#modal-size').addClass('modal-lg');
                            $('#modal .modal-body').html(data);
                        break;
                    }
                },
                complete: function (data) {
                    $('.loading-bar').addClass('hidden');
                }
            });
        }
        function editMenu(data)
        {
            data                = data.id.split('_');
            menu_id      = data[2];
            $.ajax({
                url:"<?php echo e(url('master-apps/manage-menu/edit-menu')); ?>",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    'menu_id'       : menu_id
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
                            $('#modal-title').html('Manage Menu');
                            $('#modal-size').addClass('modal-lg');
                            $('#modal .modal-body').html(data);
                        break;
                    }
                },
                complete: function (data) {
                    $('.loading-bar').addClass('hidden');
                }
            });
        }
        function changeStatusMenu(data)
        {
            if (data.checked)
            {
                status_menu     = '1';
            }
            else
            {
                status_menu     = '0';
            }
            data        = data.id.split('_');
            menu_id     = data[2];

            $.ajax({
                url:"<?php echo e(url('master-apps/manage-menu/change-status-menu')); ?>",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    'menu_id'           : menu_id,
                    'status_menu'       : status_menu
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

    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/sentul-integrated-system-stable/resources/views/masterapp/manage_menu/index.blade.php ENDPATH**/ ?>