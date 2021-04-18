<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card  card-outline">
                <div class="card-header bg-dark">
                    Application Data
                    <div class="float-right <?php echo e(Session::get('create')); ?>">
                        <button class="btn btn-outline-primary text-white" onclick="modalAddApplication()">
                            <i class="fas fa-plus"></i>&nbsp;&nbsp;New Application
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered" id="application-table" style="min-width: 100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Application Name</th>
                                <th>Application Description</th>
                                <th>Application Link</th>
                                <th>Status</th>
                                <th style="width: 80px">#</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th class="search-col">Application Name</th>
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
        var table = $('#application-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?php echo e(url('master-apps/manage-application/get-data')); ?>",
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
                    data: 'application_name',
                    name: 'application_name'
                },

                {
                    data: 'application_description',
                    name: 'application_description'
                },

                {
                    data: 'application_link',
                    name: 'application_link'
                },

                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                },
            ],
        });
        // Setup - add a text input to each footer cell
        $('#application-table tfoot .search-col').each( function (i) {
            var title = $('#application-table thead th').eq( $(this).index() ).text();
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
        function changeStatusApplication(data)
        {
            if (data.checked)
            {
                status_application     = '1';
            }
            else
            {
                status_application     = '0';
            }
            data        = data.id.split('_');
            application_id     = data[2];

            $.ajax({
                url:"<?php echo e(url('master-apps/manage-application/change-status-application')); ?>",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    'application_id'           : application_id,
                    'status_application'       : status_application
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
        function editApplication(data)
        {
            data                = data.id.split('_');
            application_id      = data[2];
            $.ajax({
                url:"<?php echo e(url('master-apps/manage-application/edit-application')); ?>",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    'application_id'       : application_id
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
        function updateApplication()
        {
            data    = $('#edit-application-form').serialize();
            $.ajax({
                url:"<?php echo e(url('master-apps/manage-application/update-application')); ?>",
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
        function modalAddApplication()
        {
            $.ajax({
                url:"<?php echo e(url('master-apps/manage-application/add-new-application-modal')); ?>",
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
        function addApplication()
        {
            data    = $('#add-application-form').serialize();
            $.ajax({
                url:"<?php echo e(url('master-apps/manage-application/add-new-application')); ?>",
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
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/sentul-integrated-system-stable/resources/views/master_app/manage_applications/index.blade.php ENDPATH**/ ?>