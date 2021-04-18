<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card  card-outline">
                <div class="card-header bg-dark">
                    <div class="row">
                        <?php if(count($rpd_filling_active) > 1): ?>
                            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-3 col-3">
                                RPD Filling Produk
                            </div>
                            <div class="col-xl-10 col-lg-10 col-md-9 col-sm-9 col-9">
                                <select name="rpd_filling_another" id="rpd_filling_another" onchange="changeRPDForm(this.value)" class="form-control">
                                    <?php $__currentLoopData = $rpd_filling_active; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list_rpd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($list_rpd->encrypt_id); ?>" <?php if($list_rpd->encrypt_id == $rpd_filling_head->encrypt_id): ?> selected <?php endif; ?>> <?php echo e($list_rpd->product->product_name); ?> </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                        <?php else: ?>
                            RPD Filling Produk <?php echo e($rpd_filling_head->product->product_name); ?>

                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label for="product_name">Product Name</label>
                                <input type="text" name="product_name" id="product_name" class="form-control" value="<?php echo e($rpd_filling_head->product->product_name); ?>" readonly>
                                <input type="hidden" name="product_type_id" id="product_type_id" class="form-control" value="<?php echo e($rpd_filling_head->product->encrypt_product_type_id); ?>" readonly>
                                <input type="hidden" name="encrypt_id" id="encrypt_id" class="form-control" value="<?php echo e($rpd_filling_head->encrypt_id); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="production_date">Production Date</label>
                                <textarea name="production_date" id="production_date" rows="3" class="form-control" readonly><?php $__currentLoopData = $rpd_filling_head->woNumbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wo_number): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($wo_number->wo_number); ?> => <?php echo e($wo_number->production_realisation_date); ?>&#13;&#10;<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="sum_batch">&#x3A3; Batch</label>
                                <input type="text" name="sum_batch" id="sum_batch" value="<?php echo e(count($rpd_filling_head->woNumbers)); ?> Batch" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                            <div class="form-group">
                                <button class="btn btn-primary form-control" onclick="addSampelPopUp()">
                                    <i class="fas fa-plus"></i>&nbsp;&nbsp;Sample Filling
                                </button>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                            <div class="form-group">
                                <button class="btn btn-outline-primary form-control" onclick="addBatch()"><i class="fas fa-plus"></i>&nbsp;&nbsp;Batch / Wo Number</button>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                            <div class="form-group">
                                <button class="btn btn-secondary form-control"><i class="fas fa-eye"></i>&nbsp;&nbsp;Draft PPQ</button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <hr>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <div class="card-header bg-warning" data-toggle="collapse" data-target="#draft-analisa-card">
                                    <strong class="text-white">Draft Analisa QC</strong>
                                </div>
                                <div class="card-body collapse show" id="draft-analisa-card">
                                    <div class="table-responsive">
                                        <table class="table display nowrap" id="draft-sampel-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nomor Wo</th>
                                                    <th>Mesin Filling</th>
                                                    <th>Jam Filling</th>
                                                    <th>Sampel Filling</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <div class="card-header bg-success" data-toggle="collapse" data-target="#done-analisa-card">
                                    <strong>Done Analisa QC</strong>
                                </div>
                                <div class="card-body collapse show" id="done-analisa-card">
                                    <div class="table-responsive">
                                        <table class="table display nowrap" id="done-sampel-rpd-filling">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nomor Wo</th>
                                                    <th>Mesin Filling</th>
                                                    <th>Jam Filling</th>
                                                    <th>Sampel Filling</th>
                                                    <th>Status Analisa</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom-plugin'); ?>

    <script>
        var iTableCounter           = 1;
        var oInnerTable;
        var rpd_filling_head_id     = $('#encrypt_id').val();
        var table = $('#draft-sampel-table').DataTable({
            processing: true,
            serverSide: true,
            // "scrollX": true,
            ajax: {
                url: "<?php echo e(url('rollie/rpd-filling/get-draft-filling-sampel')); ?>",
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
                    d.rpd_filling_head_id   = rpd_filling_head_id;
                }
            },
            columns:
            [
                {
                    data: 'action',
                    name: 'action'
                },
                {
                    data: 'wo_number',
                    name: 'wo_number'
                },

                {
                    data: 'filling_machine_code',
                    name: 'filling_machine_code'
                },
                {
                    data: 'filling_time',
                    name: 'filling_time'
                },

                {
                    data: 'filling_sampel_code',
                    name: 'filling_sampel_code'
                },
            ],
            "order": [[ 3, "asc" ]],
            rowCallback: function (row, data)
            {
                /* switch (data.production_status)
                {
                    case 'WIP Mixing':
                        $(row).addClass('bg-1');
                    break;

                    case 'In Progress Mixing':
                        $(row).addClass('bg-2');
                    break;

                    case 'WIP Fillpack':
                        $(row).addClass('bg-3');
                    break;

                    case 'In Progress Fillpack':
                        $(row).addClass('bg-3');
                    break;

                    case 'Waiting For Close':
                        $(row).addClass('bg-4');
                    break;

                    case 'Closed Wo':
                        $(row).addClass('bg-5');
                    break;

                    case 'Canceled Schedule':
                        $(row).addClass('bg-6');
                    break;


                } */
            }
        });
        // Setup - add a text input to each footer cell
        $('#draft-sampel-table tfoot .search-col').each( function (i) {
            var title = $('#draft-sampel-table thead th').eq( $(this).index() ).text();
            $(this).html( '<input type="text" placeholder="Search '+title+'" data-index="'+$(this).index()+'" class="form-control"/>' );
        } );

        $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
            table
                .column( $(this).data('index') )
                .search( this.value )
                .draw();
        } );

        var table_done = $('#done-sampel-rpd-filling').DataTable({
            processing: true,
            serverSide: true,
            // "scrollX": true,
            ajax: {
                url: "<?php echo e(url('rollie/rpd-filling/get-done-filling-sampel')); ?>",
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
                    d.rpd_filling_head_id   = rpd_filling_head_id;
                }
            },
            columns:
            [
                {
                    data: 'action',
                    name: 'action'
                },
                {
                    data: 'wo_number',
                    name: 'wo_number'
                },

                {
                    data: 'filling_machine_code',
                    name: 'filling_machine_code'
                },
                {
                    data: 'filling_time',
                    name: 'filling_time'
                },

                {
                    data: 'filling_sampel_code',
                    name: 'filling_sampel_code'
                },

                {
                    data: 'status_akhir',
                    name: 'status_akhir'
                },
            ],
            "order": [[ 0, "asc" ]],
            rowCallback: function (row, data)
            {
                /* switch (data.production_status)
                {
                    case 'WIP Mixing':
                        $(row).addClass('bg-1');
                    break;

                    case 'In Progress Mixing':
                        $(row).addClass('bg-2');
                    break;

                    case 'WIP Fillpack':
                        $(row).addClass('bg-3');
                    break;

                    case 'In Progress Fillpack':
                        $(row).addClass('bg-3');
                    break;

                    case 'Waiting For Close':
                        $(row).addClass('bg-4');
                    break;

                    case 'Closed Wo':
                        $(row).addClass('bg-5');
                    break;

                    case 'Canceled Schedule':
                        $(row).addClass('bg-6');
                    break;


                } */
            }
        });

    </script>
    <script>
        function addSampelPopUp()
        {
            rpd_filling_head_id        = $('#encrypt_id').val();
            $.ajax({
                url:"<?php echo e(url('rollie/rpd-filling/add-filling-sampel-modal')); ?>",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    'rpd_filling_head_id':rpd_filling_head_id
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
                            $('#modal-title').html('Tambah Sampel Analisa');
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
        function addBatch()
        {
            rpd_filling_head_id        = $('#encrypt_id').val();
            $.ajax({
                url:"<?php echo e(url('rollie/rpd-filling/add-batch-modal')); ?>",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    'rpd_filling_head_id':rpd_filling_head_id
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
                            $('#modal-title').html('Tambah Batch Produk');
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
        function changeRPDForm(rpd_filling_head_id)
        {
            url     = "<?php echo e(url('rollie/rpd-filling/form')); ?>/"+rpd_filling_head_id;
            window.location.href=url;
        }
        function analisa_sampel_pi(data)
        {
            data                    = data.id.split("_");
            rpd_filling_detail_id   = data[0];
            event_sampel            = data[1];
            $.ajax({
                url:"<?php echo e(url('rollie/rpd-filling/analisa-filling-sampel-modal')); ?>",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    'rpd_filling_detail_id' : rpd_filling_detail_id,
                    'event_sampel'          : event_sampel
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
                            if (event_sampel == 'event')
                            {
                                $('#modal-title').html('Analisa Sampel PI At Event');
                            }
                            else
                            {
                                $('#modal-title').html('Analisa Sampel Package Integrity');
                            }
                            $('#modal').modal('show');
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/sentul-integrated-system-stable/resources/views/rollie/rpd_filling/form.blade.php ENDPATH**/ ?>