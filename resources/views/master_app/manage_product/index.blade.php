@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card  card-outline">
                <div class="card-header bg-dark">
                    Product Data
                    <div class="float-right {{ Session::get('create') }}">
                        <button class="btn btn-outline-primary text-white" onclick="modalAddProduct()">
                            <i class="fas fa-plus"></i>&nbsp;&nbsp;New Product
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="product-table" >
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>#</th>
                                    <th style="width: 300px">Brand</th>
                                    <th>Product Type</th>
                                    <th >Product Name</th>
                                    <th>Oracle Code</th>
                                    <th>Trial Code</th>
                                    <th>Expired Date </th>
                                    <th>TS Spec</th>
                                    <th>pH Spec</th>
                                    <th>SLA</th>
                                    <th>Waktu Analisa Mikro</th>
                                    <th>Waktu Inkubasi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th class="search-col-custom"> Brand </th>
                                    <th class="search-col-custom-1"> Product Type </th>
                                    <th class="search-col">Product Name</th>
                                    <th class="search-col">Oracle Code</th>
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

    {{-- filter select2 --}}
    <select name="brand_select" id="brand_select" class="hidden">
       @foreach ($subbrands as $subbrand)
           <option value="{{ $subbrand->subbrand_name }}">{{ $subbrand->subbrand_name }}</option>
       @endforeach
    </select>
    <select name="product_type_select" id="product_type_select" class="hidden">
        @foreach ($product_types as $product_type)
            <option value="{{ $product_type->product_type }}">{{ $product_type->product_type }}</option>
        @endforeach
     </select>
@endsection


@section('custom-plugin')
    <script>
        var iTableCounter=1;
        var oInnerTable;
        var table = $('#product-table').DataTable({
            processing: true,
            serverSide: true,
            // "scrollX": true,
            ajax: {
                url: "{{ url('master-apps/manage-product/get-data') }}",
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
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'brand_name',
                    name: 'brand_name'
                },

                {
                    data: 'product_type',
                    name: 'product_type'
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
                    data: 'trial_code',
                    name: 'trial_code'
                },

                {
                    data: 'expired_date',
                    name: 'expired_date'
                },

                {
                    data: 'spek_ts',
                    name: 'spek_ts'
                },

                {
                    data: 'spek_ph',
                    name: 'spek_ph'
                },

                {
                    data: 'sla',
                    name: 'sla'
                },

                {
                    data: 'waktu_analisa_mikro',
                    name: 'waktu_analisa_mikro'
                },

                {
                    data: 'waktu_inkubasi',
                    name: 'waktu_inkubasi'
                }
            ],
            initComplete: function () {
                this.api().columns().every( function (i)
                {
                    if (i==2)
                    {
                        var column = this;
                        var select = $('<select id="brand_filter_select" class="form-control select2"><option value=""></option></select>')
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
                    if (i==3)
                    {
                        var column = this;
                        var select = $('<select id="product_type_filter_select" class="form-control select2"><option value=""></option></select>')
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
        $('#product-table tfoot .search-col').each( function (i) {
            var title = $('#product-table thead th').eq( $(this).index() ).text();
            $(this).html( '<input type="text" placeholder="Search '+title+'" data-index="'+$(this).index()+'" class="form-control"/>' );
        } );

        $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
            table
                .column( $(this).data('index') )
                .search( this.value )
                .draw();
        } );

        var $option_brand = $("#brand_select > option").clone();
        setTimeout(function(){  $('#brand_filter_select').append($option_brand); }, 2000);

        var $product_type_filter_select = $("#product_type_select > option").clone();
        setTimeout(function(){  $('#product_type_filter_select').append($product_type_filter_select); }, 2000);


        function modalAddProduct()
        {
            $.ajax({
                url:"{{url('master-apps/manage-product/add-new-product-modal')}}",
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
                            $('#modal-title').html('Manage Product');
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

        function editProduct(data)
        {
            data            = data.id.split('_');
            product_id      = data[2];
            $.ajax({
                url:"{{url('master-apps/manage-product/edit-product-modal')}}",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    'product_id'       : product_id
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

@endsection

