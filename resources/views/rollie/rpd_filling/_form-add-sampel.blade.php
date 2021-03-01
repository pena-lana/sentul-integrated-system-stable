    <div class="row">
        <div class="col-md-12 col-sm-12 col-12">
            <form id="add-filling-sampel-code-form">
                <div class="form-group">
                    <label for="wo_number_id">Nomor Wo</label>
                    <select name="wo_number_id" id="wo_number_id" class="select selectcustom2 form-control" required>
                        @php
                            $batch_ke =1;
                        @endphp
                        @foreach ($rpd_filling_head->woNumbers as $wo_number)
                            <option value="{{ $wo_number->encrypt_id }}" @if ($wo_number->wo_number === $last_wo_number->wo_number) selected @endif> Batch {{ $batch_ke }}- {{ $wo_number->wo_number }}</option>

                            @php
                                $batch_ke++;
                            @endphp
                        @endforeach
                    </select>
                    <input type="hidden" value="{{$rpd_filling_head->encrypt_id}}" name="rpd_filling_head_id" id="rpd_filling_head_id">
                </div>
                <div class="form-group">
                    <label for="filling_machine_id">Mesin Filling</label>
                    <select name="filling_machine_id" id="filling_machine_id" class="form-control select2" onchange="getFillingSampelCode(this)" required>
                        <option value="none" selected disabled>Pilih Satu Mesin Filling</option>
                        @foreach ($last_wo_number->filling_machine_group as $filling_machine_group)
                            <option value="{{ $filling_machine_group->filling_machine->encrypt_id }}">{{ $filling_machine_group->filling_machine->filling_machine_code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="filling_sampel_code">Kode Sampel Filling</label>
                    <select name="filling_sampel_code" id="filling_sampel_code" class="form-control select2" onchange="checkFillingSampelCode(this)" required>
                        <option value="none" disabled selected>Pilih Satu Kode Sampel Filling</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filling_event_note">Keterangan Event Filling</label>
                    <select name="filling_event_note" id="filling_event_note" class="form-control select2" required>
                        <option value="none" disabled selected>Pilih Satu Keterangan Event Filling</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filling_date">Filling Date</label>
                    <div class="input-group date mb-2 datepickernya" data-target-input="nearest">
                        <div class="input-group-append"  onclick="$('#filling_date').click()">
                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                        </div>
                        <input type="text" class="form-control datetimepicker-input" name="filling_date" id="filling_date" data-target=".datepickernya" data-toggle="datetimepicker"/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="filling_time">Filling Time</label>
                    <div class="input-group date mb-2 timepickernya" data-target-input="nearest">
                        <div class="input-group-append" onclick="$('#filling_time').click()">
                            <div class="input-group-text"><i class="fas fa-clock"></i></div>
                        </div>
                        <input type="text" class="form-control datetimepicker-input" name="filling_time" id="filling_time" data-target=".timepickernya" data-toggle="datetimepicker"  />
                    </div>
                </div>
                <div class="form-group" id="berat_kanan_div">
                    <label for="berat_kanan">Berat Kanan</label>
                    <input type="text" inputmode="decimal" autocomplete="off"  name="berat_kanan" id="berat_kanan" class="form-control" maxlength="6" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode !== 47" onfocusout="change_berat(this)" required>
                    <small id="berat_kanan_help_text" class="form-text text-muted">
                        Hanya di isi angka dengan format ratusan desimal dan menggunakan titik ( . ) sebagai desimal.
                    </small>
                </div>

                <div class="form-group" id="berat_kiri_div">
                    <label for="berat_kiri">Berat Kiri</label>
                    <input type="text" name="berat_kiri" inputmode="decimal" autocomplete="off" id="berat_kiri" class="form-control" maxlength="6" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode !== 47" onfocusout="change_berat(this)" required>
                    <small id="berat_kiri_help_text" class="form-text text-muted">
                        Hanya di isi angka dengan format ratusan desimal dan menggunakan titik ( . ) sebagai desimal.
                    </small>
                </div>
                <div class="row mt-2">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <button class="btn btn-outline-secondary form-control" onclick="$('.close').click()">
                            Batal
                        </button>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <button class="btn btn-primary form-control">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script>
        $('.timepickernya').datetimepicker({
            format: 'HH:mm:ss',
            locale:'en',
            date: new Date()
        });
        $('.datepickernya').datetimepicker({
            format: 'YYYY-MM-DD',
            locale:'en',
            date: new Date()
        });

        $('.datepicker').datetimepicker({
            format: 'YYYY-MM-DD',
            locale:'en'
        });
        $('.datetimepickernya').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
        });
    </script>
    <script>
        function change_berat(params)
        {
            var id      = params.id;
            var isi     = params.value;
            if (isi.includes('-') || isi.includes(','))
            {
                Swal.fire({
                    title   : 'Process Error !',
                    text    : "Field Berat hanya di isi angka dengan format desimal mengunakan titik sebagai desimal.",
                    icon    : 'error'
                });
                return false;
            }
            else if(isi.includes('.'))
            {
                check_isi   = isi.toString().split('.');
                if (check_isi[0].length > 3 || check_isi[1].length > 2 )
                {
                    Swal.fire({
                        title   : 'Process Error !',
                        text    : "Field Berat hanya di isi angka dengan format ratusan desimal dengan maksimal 2 angka dibelakang koma.",
                        icon    : 'error'
                    });
                    return false;
                }
            }
        }
        $(document).ready(function()
        {
            $('.select2').select2({
                    'theme':'bootstrap4'
            });
            var form        = $('#add-filling-sampel-code-form');
            form.validate({
                rules:
                {
                    wo_number_id :{
                        required:true
                    },
                    filling_machine_id :{
                        required:true
                    },
                    filling_sampel_code :{
                        required:true
                    },
                    filling_event_note :{
                        required:true
                    },
                    filling_date :{
                        required:true
                    },
                    filling_time :{
                        required:true
                    },
                    berat_kanan :{
                        required:true,
                        number:true
                    },
                    berat_kiri :{
                        required:true,
                        number:true
                    }
                },
                messages:
                {
                    wo_number_id :{
                        required:"Data tidak boleh kosong"
                    },
                    filling_machine_id :{
                        required:"Data tidak boleh kosong"
                    },
                    filling_sampel_code :{
                        required:"Data tidak boleh kosong"
                    },
                    filling_event_note :{
                        required:"Data tidak boleh kosong"
                    },
                    filling_date :{
                        required:"Data tidak boleh kosong"
                    },
                    filling_time :{
                        required:"Data tidak boleh kosong"
                    },
                    berat_kanan :{
                        required:"Data tidak boleh kosong"
                    },
                    berat_kiri :{
                        required:"Data tidak boleh kosong"
                    }
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    data            = $('#add-filling-sampel-code-form').serialize();
                    $.ajax({
                        url:"{{url('rollie/rpd-filling/add-filling-sampel-code')}}",
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
                                    $('.close').click();
                                    table.ajax.reload(null,false);
                                    table_done.ajax.reload(null,false);

                                break;
                                case '01':
                                    Swal.fire({
                                        title: 'Process Error ! ',
                                        text: data.message,
                                        icon: 'error'
                                    });
                                break;

                                case '02':
                                    Swal.fire({
                                        title: 'Process Error !',
                                        text: data.message,
                                        icon: 'error'
                                    });
                                    $('.close').click();
                                break;
                            }
                        },
                        complete: function (data) {
                            $('.loading-bar').addClass('hidden');
                        }
                    });
                }


            });
        });
        function getFillingSampelCode(data)
        {
            filling_machine_id          = data.value;
            product_type_id             = $('#product_type_id').val();
            $.ajax({
                url:"{{url('rollie/rpd-filling/get-filling-sampel-code')}}",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    'filling_machine_id': filling_machine_id,
                    'product_type_id'   : product_type_id
                },
                success: function(data)
                {
                    switch (data.status)
                    {
                        case '00':
                            $('#filling_sampel_code').append(data.option_filling_sampel_code);
                        break;
                        case '01':
                            Swal.fire({
                                title: 'Process Error ! ',
                                text: data.message,
                                icon: 'error'
                            });
                        break;

                        case '02':
                            Swal.fire({
                                title: 'Process Error !',
                                text: data.message,
                                icon: 'error'
                            });
                            $('.close').click();
                        break;
                    }
                },
                complete: function (data) {
                    $('.loading-bar').addClass('hidden');
                }
            });

        }

        function checkFillingSampelCode(data)
        {
            filling_sampel_code_id          = data.value;
            $.ajax({
                url:"{{url('rollie/rpd-filling/check-filling-sampel-code')}}",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    'filling_sampel_code_id': filling_sampel_code_id
                },
                success: function(data)
                {
                    switch (data.status)
                    {
                        case '00':
                            $('#filling_event_note').empty();
                            $('#filling_event_note').append(data.option);
                            if(data.pi == 0)
                            {
                                $('#berat_kanan').val('000.00');
                                $('#berat_kiri').val('000.00');
                                $('#berat_kanan_div').hide();
                                $('#berat_kiri_div').hide();
                            }
                            else
                            {
                                $('#berat_kanan').val('');
                                $('#berat_kiri').val('');
                                $('#berat_kanan_div').show();
                                $('#berat_kiri_div').show();
                            }
                        break;
                        case '01':
                            Swal.fire({
                                title: 'Process Error ! ',
                                text: data.message,
                                icon: 'error'
                            });
                        break;

                        case '02':
                            Swal.fire({
                                title: 'Process Error !',
                                text: data.message,
                                icon: 'error'
                            });
                            $('.close').click();
                        break;
                    }
                },
                complete: function (data) {
                    $('.loading-bar').addClass('hidden');
                }
            });
        }

        /* */
    </script>
