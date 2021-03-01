@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card  card-outline">
                <div class="card-header bg-dark">
                    Draft Production Schedule
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <button class="btn btn-primary" onclick="modalUploadMtol()">
                                <i class="fas fa-upload"></i>&nbsp; Upload File MTOL
                            </button>
                            <button class="btn btn-outline-primary" onclick="modalManualAdd()">
                                <i class="fas fa-plus"></i>&nbsp; Manual Input
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table display nowrap" id="draft-production-schedule-table" >
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
                <div class="card-footer">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <button class="btn btn-secondary" onclick="@if($data_draft == 0) document.location.href='/rollie/production-schedule' @else backToDashboard() @endif" id="button-back-to-dashboard">
                                <i class="fas fa-arrow-left"> </i> Back To On Going Production Schedule
                            </button>
                            <button class="btn btn-primary @if ($data_draft == 0) hidden @endif" id="button-finalize-draft" onclick="finalizeDraft()">
                                <i class="fas fa-check"></i> Finalize Draft Production Schedule
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-plugin')
    <script>
        var iTableCounter=1;
        var oInnerTable;
        var draft_table = $('#draft-production-schedule-table').DataTable({
            processing: true,
            serverSide: true,
            // "scrollX": true,
            ajax: {
                url: "{{ url('rollie/production-schedule/get-data-draft') }}",
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
            ]

        });
        // Setup - add a text input to each footer cell
        $('#draft-production-schedule-table tfoot .search-col').each( function (i) {
            var title = $('#draft-production-schedule-table thead th').eq( $(this).index() ).text();
            $(this).html( '<input type="text" placeholder="Search '+title+'" data-index="'+$(this).index()+'" class="form-control"/>' );
        } );

        $( draft_table.table().container() ).on( 'keyup', 'tfoot input', function () {
            draft_table
                .column( $(this).data('index') )
                .search( this.value )
                .draw();
        } );
    </script>
    <script>

        function modalUploadMtol()
        {
            $.ajax({
                url:"{{url('rollie/production-schedule/upload-mtol-modal')}}",
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
                            draft_table.ajax.reload(null,false);
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
                            $('#modal-title').html('Upload Mtol');
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

        function modalManualAdd()
        {
            $.ajax({
                url:"{{url('rollie/production-schedule/manual-add-modal')}}",
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
                            draft_table.ajax.reload(null,false);
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
                            $('#modal-title').html('Add Production Schedule Manually');
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

        function deleteDraftProductionSchedule(data)
        {
            data                = data.id.split('_');
            product_name        = data[0];
            wo_number           = data[1];
            wo_id               = data[2];
            Swal.fire({
                    icon:'question',
                    title: 'Penghapusan Jadwal',
                    showConfirmButton: false,
                    showDenyButton: true,
                    showCancelButton: true,
                    html: 'Apakah kamu yakin akan menghapus jadwal produksi <b>'+ product_name +'</b> dengan nomor wo <b>'+wo_number+'</b> ?',
                    denyButtonText: '<i class="fas fa-trash"></i> Hapus Jadwal!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if(result.isDenied)
                {
                    $.ajax({
                        url:"{{url('rollie/production-schedule/remove-draft-schedule')}}",
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
                                    draft_table.ajax.reload(null,false);
                                break;
                                case '01':
                                    Swal.fire({
                                        title: 'Process Error ! ',
                                        text: data.message,
                                        icon: 'error'
                                    });
                                    draft_table.ajax.reload(null,false);
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

        function updateDraftProductionSchedule(data)
        {
            data                = data.id.split('_');
            wo_id               = data[2];
            $.ajax({
                url:"{{url('rollie/production-schedule/update-draft-schedule-modal')}}",
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
                            draft_table.ajax.reload(null,false);
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

        function backToDashboard()
        {
            Swal.fire({
                    icon:'question',
                    title: 'Leave Draft Production Schedule',
                    showConfirmButton: false,
                    showDenyButton: true,
                    showCancelButton: true,
                    html: 'Apakah kamu yakin meninggalkan halaman penambahan jadwal? Jadwal yang belum di finalize tidak akan masuk kedalam dashboard jadwal produksi',
                    denyButtonText: '<i class="fas fa-door-open"></i> Ya, Tinggalkan halaman draft jadwal!',
                    cancelButtonText: 'Kembali Ke Form Draft Jadwal',
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if(result.isDenied)
                {
                    document.location.href='/rollie/production-schedule';
                }
            })
        }
        function finalizeDraft()
        {
            Swal.fire({
                    icon:'question',
                    title: 'Finalize Draft Schedule',
                    showConfirmButton: true,
                    showDenyButton: false,
                    showCancelButton: true,
                    html: 'Apakah kamu yakin akan finalize seluruh draft jadwal produksi?',
                    confirmButtonText: '<i class="fas fa-check"></i> Ya, Finalize Draft Jadwal!',
                    cancelButtonText: 'Kembali Ke Form Draft Jadwal',
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if(result.isConfirmed)
                {
                    $.ajax({
                        url:"{{url('rollie/production-schedule/finalize-draft-schedule')}}",
                        type:'POST',
                        beforeSend:function()
                        {
                            $('.loading-bar').removeClass('hidden');
                        },
                        headers: {
                            'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                        },
                        data:'draft',
                        success: function(data)
                        {
                            switch (data.status)
                            {
                                case '00':
                                    Swal.fire({
                                        title: 'Process Success !',
                                        text: data.message,
                                        icon: 'success'
                                    });
                                    setTimeout(function(){ document.location.href='/rollie/production-schedule' }, 2000);
                                break;
                                case '01':
                                    Swal.fire({
                                        title: 'Process Error ! ',
                                        text: data.message,
                                        icon: 'error'
                                    });
                                    draft_table.ajax.reload(null,false);
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


@endsection

