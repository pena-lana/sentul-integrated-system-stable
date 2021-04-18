<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card  card-outline">
                <div class="card-header bg-dark">
                    Filling Machine Group Data
                    <div class="float-right <?php echo e(Session::get('create')); ?>">
                        <button class="btn btn-outline-primary text-white" onclick="modalAddFillingMachineGroup()">
                            <i class="fas fa-plus"></i>&nbsp;&nbsp;Filling Machine Group
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered" id="filling-machine-group-table" >
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="hidden">Filling Machine Group Id</th>
                                <th>Filling Machine Group Name</th>
                                <th>Status</th>
                                <th style="width: 400px"></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th class="hidden"></th>
                                <th class="search-col">Filling Machine Group Name</th>
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
                        '<th>Filling Machine Name</th>'+
                        '<th style="width:400px">Filling Machine Code</th>'+
                        '<th>#</th>'+
                        '</tr>'+
                        '</thead'+
            '</table>';
        }
        var iTableCounter=1;
        var oInnerTable;
        var table = $('#filling-machine-group-table').DataTable({
            rowId: 'encrypt_id',
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?php echo e(url('master-apps/manage-filling-machine-group/get-data')); ?>",
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
                    data: 'filling_machine_group_name',
                    name: 'filling_machine_group_name'
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
        // Setup - add a text input to each footer cell
        $('#filling-machine-group-table tfoot .search-col').each( function (i) {
            var title = $('#filling-machine-group-table thead th').eq( $(this).index() ).text();
            $(this).html( '<input type="text" placeholder="Search '+title+'" data-index="'+$(this).index()+'" class="form-control"/>' );
        } );

        $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
            table
                .column( $(this).data('index') )
                .search( this.value )
                .draw();
        } );

        $('#filling-machine-group-table tbody').on('click', 'td a.details-control', function () {
            let tr                                  = $(this).closest('tr');
            let row                                 = table.row( tr );
            let filling_machine_group_head_id       = tr[0]['id'];
            if ( row.child.isShown() )
            {
                row.child.hide();
                tr.removeClass('shown');
            }
            else
            {
                row.child( format(filling_machine_group_head_id) ).show();
                tr.addClass('shown');
               /*  $('#table-data_'+filling_machine_group_head_id).DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "<?php echo e(url('master-apps/manage-filling-machine-group/get-filling-machine-detail')); ?>",
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
                            d.filling_machine_group_head_id= filling_machine_group_head_id;
                        }
                    },
                    columns:
                    [
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'filling_machine_name',
                            name: 'filling_machine_name'
                        },

                        {
                            data: 'filling_machine_code',
                            name: 'filling_machine_code'
                        },

                        {
                            data: 'status',
                            name: 'status'
                        }
                    ]
                }); */
                $('#table-data_'+filling_machine_group_head_id).DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "<?php echo e(url('master-apps/manage-filling-machine-group/get-filling-machine-detail')); ?>",
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
                            d.filling_machine_group_head_id = filling_machine_group_head_id;
                        }
                    },
                    columns:
                    [
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'filling_machine_name',
                            name: 'filling_machine_name'
                        },

                        {
                            data: 'filling_machine_code',
                            name: 'filling_machine_code'
                        },

                        {
                            data: 'status',
                            name: 'status'
                        }
                    ]
                });
                iTableCounter = iTableCounter + 1;
            }
        });



        function changeStatusFillingMachineGroupHead(data)
        {
            if (data.checked)
            {
                status_filling_machine_group_head     = '1';
            }
            else
            {
                status_filling_machine_group_head     = '0';
            }
            data                    = data.id.split('_');
            filling_machine_group_head_id     = data[3];

            $.ajax({
                url:"<?php echo e(url('master-apps/manage-filling-machine-group/change-status-filling-machine-group')); ?>",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    'filling_machine_group_head_id'             : filling_machine_group_head_id,
                    'status_filling_machine_group_head'         : status_filling_machine_group_head
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

        function editFillingMachineGroupHead(data)
        {
            data                                = data.id.split('_');
            filling_machine_group_head_id       = data[3];
            $.ajax({
                url:"<?php echo e(url('master-apps/manage-filling-machine-group/edit-filling-machine-group-head')); ?>",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    'filling_machine_group_head_id'       : filling_machine_group_head_id
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
                            $('#modal-title').html('Manage Filling Machine');
                            $('#modal .modal-body').html(data);
                        break;
                    }
                },
                complete: function (data) {
                    $('.loading-bar').addClass('hidden');
                }
            });
        }
        function changeStatusFillingMachineGroupDetail(data)
        {

            if (data.checked)
            {
                status_filling_machine_group_detail     = '1';
            }
            else
            {
                status_filling_machine_group_detail     = '0';
            }
            data                                = data.id.split('_');
            filling_machine_group_detail_id     = data[3];
            filling_machine_group_head_id       = data[4];
            console.log(filling_machine_group_head_id);

            $.ajax({
                url:"<?php echo e(url('master-apps/manage-filling-machine-group/change-status-filling-machine-group-detail')); ?>",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    'filling_machine_group_detail_id'             : filling_machine_group_detail_id,
                    'status_filling_machine_group_detail'         : status_filling_machine_group_detail
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
                            $('#table-data_'+filling_machine_group_head_id).DataTable().ajax.reload(null,false);
                        break;

                        case '01':
                            Swal.fire({
                                title: 'Process Error ! ',
                                text: data.message,
                                icon: 'error'
                            });
                            $('#table-data_'+filling_machine_group_head_id).DataTable().ajax.reload(null,false);
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
        function createFillingMachineGroupHead(data)
        {
            data                                = data.id.split('_');
            filling_machine_group_head_id       = data[3];
            $.ajax({
                url:"<?php echo e(url('master-apps/manage-filling-machine-group/add-filling-machine-group-detail-modal')); ?>",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    'filling_machine_group_head_id'       : filling_machine_group_head_id
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
                            $('#modal-title').html('Manage Filling Machine');
                            /* $('#modal-size').addClass('modal-lg'); */
                            $('#modal .modal-body').html(data);
                        break;
                    }
                },
                complete: function (data) {
                    $('.loading-bar').addClass('hidden');
                }
            });
        }
        function modalAddFillingMachineGroup()
        {
            $.ajax({
                url:"<?php echo e(url('master-apps/manage-filling-machine-group/add-new-filling-machine-group-modal')); ?>",
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
                            $('#modal-title').html('Manage Filling Machine');
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
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/sentul-integrated-system-stable/resources/views/master_app/manage_filling_machine_group/index.blade.php ENDPATH**/ ?>