@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card  card-outline">
                <div class="card-header bg-dark">
                    Filling Machine Data
                    <div class="float-right {{ Session::get('create') }}">
                        <button class="btn btn-outline-primary text-white" onclick="modalAddFillingMachine()">
                            <i class="fas fa-plus"></i>&nbsp;&nbsp;Filling Machine
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered" id="filling_machine_table" style="min-width: 100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Filling Machine Name</th>
                                <th>Filling Machine Code</th>
                                <th>Status</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th class="search-col">Filling Machine Name</th>
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
@endsection

@section('custom-plugin')
    <script>
        function format ( table_id ) {
            return '<table class="table table-hover" width="100%" id="table-data-'+table_id+'">'+
                        '<thead>'+
                        '<tr>'+
                        '<th>#</th>'+
                        '<th>FillingMachine Name</th>'+
                        '<th>FillingMachine Route</th>'+
                        '<th>FillingMachine Position</th>'+
                        '<th>Status</th>'+
                        '<th>#</th>'+
                        '</tr>'+
                        '</thead'+
            '</table>';
        }
        var iTableCounter=1;
        var oInnerTable;
        var table = $('#filling_machine_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('master-apps/manage-filling-machine/get-data') }}",
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
                },
                {
                    data: 'action',
                    name: 'action',
                },
            ]
        });
        // Setup - add a text input to each footer cell
        $('#filling_machine_table tfoot .search-col').each( function (i) {
            var title = $('#filling_machine_table thead th').eq( $(this).index() ).text();
            $(this).html( '<input type="text" placeholder="Search '+title+'" data-index="'+$(this).index()+'" class="form-control"/>' );
        } );

        $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
            table
                .column( $(this).data('index') )
                .search( this.value )
                .draw();
        } );

        function changeStatusFillingMachine(data)
        {
            if (data.checked)
            {
                status_filling_machine     = '1';
            }
            else
            {
                status_filling_machine     = '0';
            }
            data        = data.id.split('_');
            filling_machine_id     = data[3];

            $.ajax({
                url:"{{url('master-apps/manage-filling-machine/change-status-filling-machine')}}",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    'filling_machine_id'           : filling_machine_id,
                    'status_filling_machine'       : status_filling_machine
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
        function modalAddFillingMachine()
        {
            $.ajax({
                url:"{{url('master-apps/manage-filling-machine/add-new-filling-machine-modal')}}",
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
        function editFillingMachine(data)
        {
            data                    = data.id.split('_');
            filling_machine_id      = data[3];
            $.ajax({
                url:"{{url('master-apps/manage-filling-machine/edit-filling-machine')}}",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    'filling_machine_id'       : filling_machine_id
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

@endsection
