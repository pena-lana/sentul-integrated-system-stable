@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card  card-outline">
                <div class="card-header bg-dark">
                    On Going Production Schedule
                    <div class="float-right {{ Session::get('create') }}">
                        <button class="btn btn-outline-primary text-white" onclick="document.location.href='production-schedule/add-new-production-schedule'">
                            <i class="fas fa-plus"></i>&nbsp;&nbsp; New Production Schedule
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="production-schedule-table" >
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Wo Number</th>
                                    <th >Product Name</th>
                                    <th>Oracle Code</th>
                                    <th>Production Plan Date </th>
                                    <th>Production Realisation Date </th>
                                    <th>Status Production Proses</th>
                                    <th>Plan Batch Size</th>
                                    <th>Actual Batch Size</th>
                                    <th>Keterangan 1</th>
                                    <th>Keterangan 2</th>
                                    <th>Keterangan 3</th>
                                    <th>Revisi Formula</th>
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
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
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
        @foreach ($products as $product)
            <option value="{{ $product->product_name }}">{{ $product->product_name }}</option>
        @endforeach
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
@endsection
@section('custom-plugin')
    <script>
         var iTableCounter=1;
        var oInnerTable;
        var table = $('#production-schedule-table').DataTable({
            processing: true,
            serverSide: true,
            // "scrollX": true,
            ajax: {
                url: "{{ url('rollie/production-schedule/get-data') }}",
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
                    data: 'oracle_code',
                    name: 'oracle_code'
                },

                {
                    data: 'production_plan_date',
                    name: 'production_plan_date'
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
                    data: 'plan_batch_size',
                    name: 'plan_batch_size'
                },

                {
                    data: 'actual_batch_size',
                    name: 'actual_batch_size'
                },

                {
                    data: 'explanation_1',
                    name: 'explanation_1'
                },

                {
                    data: 'explanation_2',
                    name: 'explanation_2'
                },

                {
                    data: 'explanation_3',
                    name: 'explanation_3'
                },

                {
                    data: 'formula_revision',
                    name: 'formula_revision'
                }
            ],
            "order": [[ 5, "asc" ]],
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
        $('#production-schedule-table tfoot .search-col').each( function (i) {
            var title = $('#production-schedule-table thead th').eq( $(this).index() ).text();
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
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton   : 'btn btn-primary margin-for-button',
                denyButton      : 'btn btn-outline-primary margin-for-button',
                cancelButton      : 'btn btn-outline-secondary margin-for-button'
            },
            buttonsStyling: false
        })

        function updateProductionSchedule(data)
        {
            data                = data.id.split('_');
            wo_id               = data[2];
            $.ajax({
                url:"{{url('rollie/production-schedule/update-schedule-modal')}}",
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
                            $('#modal-title').html('Edit Production Schedule');
                            $('#modal-size').addClass('modal-md');
                            $('#modal .modal-body').html(data);
                        break;
                    }
                },
                complete: function (data) {
                    $('.loading-bar').addClass('hidden');
                }
            });
        }

        function cancelProductionSchedule(data)
        {
            data                = data.id.split('_');
            product_name        = data[0];
            wo_number           = data[1];
            wo_id               = data[2];
            Swal.fire({
                    icon:'question',
                    title: 'Pembatalan Jadwal',
                    showConfirmButton: false,
                    showDenyButton: true,
                    showCancelButton: true,
                    html: 'Apakah kamu yakin akan membatalkan jadwal produksi <b>'+ product_name +'</b> dengan nomor wo <b>'+wo_number+'</b> ?',
                    denyButtonText: '<i class="fas fa-trash"></i> Ya Batalkan !',
                    cancelButtonText: 'Kembali',
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if(result.isDenied)
                {
                    Swal.fire({
                        input: 'textarea',
                        inputLabel: 'Alasan Pembatalan Jadwal',
                        inputPlaceholder: 'Ex. Perubahan jadwal produksi dikarenakan bahan baku',
                        inputAttributes: {
                            'aria-label': 'Ex. Perubahan jadwal produksi dikarenakan bahan baku'
                        },
                        showCancelButton: true,
                        confirmButtonText: "Proses Pebatalan Jadwal",
                        cancelButtonText:"Batal",
                    }).then((result) => {
                        if (result.isConfirmed)
                        {
                            if(!result.value || result.value == '')
                            {
                                Swal.fire({
                                    title: 'Process Error ! ',
                                    text: "Alasan pembatalan tidak boleh kosong",
                                    icon: 'error'
                                });
                            }
                            else
                            {
                                $.ajax({
                                    url:"{{url('rollie/production-schedule/cancel-production-schedule')}}",
                                    type:'POST',
                                    beforeSend:function()
                                    {
                                        $('.loading-bar').removeClass('hidden');
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data:{
                                        'wo_id':wo_id,
                                        'alasan_pembatan':result.value
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
                        }

                    })
                }
            })
        }
    </script>


@endsection

