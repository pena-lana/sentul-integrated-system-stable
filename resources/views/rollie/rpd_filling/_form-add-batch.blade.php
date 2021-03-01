<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <form id="add-batch-form">
            <div class="form-group">
                <label for="add_type">
                    Jenis Penambahan
                </label>
                <input type="hidden" name="rpd_filling_head_id" id="rpd_filling_head_id" class="form-control" value="{{ $rpd_filling_head_id }}">
                <select name="add_type" id="add_type" class="form-control select2custom" onchange="getWoNumber(this.value,$('#rpd_filling_head_id').val())" required>
                    <option value="none" selected disabled>Pilih Jenis Penambahan</option>
                    <option value="0">Penambahan Batch Proses</option>
                    <option value="1">Penambahan Produk Beda Mesin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="wo_number_id">Wo Number</label>
                <select name="wo_number_id" id="wo_number_id" class="form-control select2custom" required>
                    <option value="none" selected disabled>Pilih Nomor Wo</option>
                </select>
            </div>
        </form>
        <div class="row mt-2">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                <button class="btn btn-outline-secondary form-control" onclick="$('.close').click()">
                    Batal
                </button>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                <button class="btn btn-primary form-control" onclick="addBatch()">
                    Submit
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function()
    {
        $('.select2custom').select2({
                'theme':'bootstrap4'
        });
    });
    function getWoNumber(add_type,rpd_filling_head_id)
    {
        if (add_type !== 'none')
        {
            $.ajax({
                url:"{{url('rollie/rpd-filling/get-wo-number-batch')}}",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    'add_type'              : add_type,
                    'rpd_filling_head_id'   : rpd_filling_head_id
                },
                success: function(data)
                {
                    switch (data.status)
                    {
                        case '00':
                            $('#wo_number_id').empty();
                            $('#wo_number_id').append(data.option);
                            $("#wo_number_id").trigger('change');
                        break;
                        case '01':
                            $("#add_type").val("none");
                            $("#add_type").trigger('change');
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
    }
    function addBatch()
    {
        data          = $('#add-batch-form').serialize();
        $.ajax({
            url:"{{url('rollie/rpd-filling/add-batch')}}",
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
                        url     = "{{ url('rollie/rpd-filling/form/') }}"+"/"+data.rpd_filling_head_id;
                        window.location.href=url;
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
</script>
