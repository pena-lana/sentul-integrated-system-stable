<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card  card-outline">
                <div class="card-header bg-dark">
                    RPD Filling Product
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="rpd-filling-table" >
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Wo Number</th>
                                    <th>Product Name</th>
                                    <th>Production Realisation Date </th>
                                    <th>Revisi Formula</th>
                                    <th>Status Production Proses</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th class="search-col"></th>
                                    <th class="search-col-custom"></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <select name="product_select" id="product_select" class="hidden">
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($product->product_name); ?>"><?php echo e($product->product_name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
     </select>
     <select name="schedule_select" id="schedule_select" class="hidden">
            <option value="WIP Mixing">WIP Mixing</option>
            <option value="In Progress Mixing">In Progress Mixing</option>
            <option value="WIP Fillpack">WIP Fillpack</option>
            <option value="In Progress Fillpack">In Progress Fillpack</option>
            <option value="Waiting For Close">Waiting For Close</option>
            <option value="Closed Wo">Closed Wo</option>
            <option value="Canceled Wo">Canceled Wo</option>
      </select>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom-plugin'); ?>
    <script>
        var iTableCounter=1;
        var oInnerTable;
        var table = $('#rpd-filling-table').DataTable({
            processing: true,
            serverSide: true,
            // "scrollX": true,
            ajax: {
                url: "<?php echo e(url('rollie/rpd-filling/get-data')); ?>",
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
                    data: 'action',
                    name: 'action'
                },
                {
                    data: 'wo_number',
                    name: 'wo_number'
                },

                {
                    data: 'product_name',
                    name: 'product_name'
                },
                {
                    data: 'production_realisation_date',
                    name: 'production_realisation_date'
                },

                {
                    data: 'production_status',
                    name: 'production_status'
                },

                {
                    data: 'formula_revision',
                    name: 'formula_revision'
                }
            ],
            "order": [[ 2, "asc" ]],
            rowCallback: function (row, data) {
                switch (data.production_status)
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
                        $(row).addClass('bg-3-1');
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


                }
            },
            initComplete: function () {
                this.api().columns().every( function (i)
                {
                    if (i==2)
                    {
                        var column = this;
                        var select = $('<select id="product_filter_select" class="form-control select2"><option value=""></option></select>')
                            .appendTo( $(column.footer()).empty() )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );
                            $('.select2').select2({
                                    'theme':'bootstrap4'
                            });

                    }
                    if (i==6)
                    {
                        var column = this;
                        var select = $('<select id="schedule_filter_select" class="form-control select2"><option value=""></option></select>')
                            .appendTo( $(column.footer()).empty() )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );

                            $('.select2').select2({
                                    'theme':'bootstrap4'
                            });
                    }
                } );
            }
        });
        // Setup - add a text input to each footer cell
        $('#rpd-filling-table tfoot .search-col').each( function (i) {
            var title = $('#rpd-filling-table thead th').eq( $(this).index() ).text();
            $(this).html( '<input type="text" placeholder="Search '+title+'" data-index="'+$(this).index()+'" class="form-control"/>' );
        } );

        $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
            table
                .column( $(this).data('index') )
                .search( this.value )
                .draw();
        } );
        var $option_product = $("#product_select > option").clone();
        setTimeout(function(){  $('#product_filter_select').append($option_product); }, 2000);

        var $schedule_filter_select = $("#schedule_select > option").clone();
        setTimeout(function(){  $('#schedule_filter_select').append($schedule_filter_select); }, 2000);

    </script>

    <script>
        function proccessRPDFilling(data)
        {
            data                = data.id.split('_');
            product_name        = data[0];
            wo_number           = data[1];
            wo_id               = data[2];
            Swal.fire({
                    icon:'question',
                    title: 'Proses Filling Produk',
                    showConfirmButton: true,
                    showDenyButton: false,
                    showCancelButton: true,
                    html: 'Apakah kamu yakin akan memproses jadwal produksi <b>'+ product_name +'</b> dengan nomor wo <b>'+wo_number+'</b> ?',
                    confirmButtonText: 'Proses RPD Filling',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                if(result.isConfirmed)
                {
                    $.ajax({
                        url:"<?php echo e(url('rollie/rpd-filling/process-rpd-filling')); ?>",
                        type:'POST',
                        beforeSend:function()
                        {
                            $('.loading-bar').removeClass('hidden');
                        },
                        headers: {
                            'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                        },
                        data:{
                            'wo_id':wo_id
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
                                    document.location.href='rpd-filling/form/'+data.rpd_filling_head_id;
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
            })

        }
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/sentul-integrated-system-stable/resources/views/rollie/rpd_filling/index.blade.php ENDPATH**/ ?>