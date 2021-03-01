<form method="post" id="add-product-form">
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label for="product_name">Nama Produk</label>
                <input required="true" autocomplete="off" type="text" class="form-control" id="product_name" name="product_name">
            </div>
            <div class="form-group">
                <label for="oracle_code">Kode Oracle</label>
                <input required="true" autocomplete="off" type="text" class="form-control" id="oracle_code" name="oracle_code">
            </div>
            <div class="form-group">
                <label for="subbrand_id">Brand</label>
                <select name="subbrand_id" id="subbrand_id" class="form-control">
                    <option value="id" selected disabled>-- Pilih Brand -- </option>
                    @foreach ($subbrands as $subbrand)
                        <option value="{{ $subbrand->encrypt_id }}">{{ $subbrand->subbrand_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="product_type_id">Jenis Produk</label>
                <select name="product_type_id" id="product_type_id" class="form-control">
                    <option value="id" selected disabled>-- Pilih Jenis Produk -- </option>
                    @foreach ($product_types as $product_type)
                        <option value="{{ $product_type->encrypt_id }}">{{ $product_type->product_type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="filling_machine_group_head_id">Jenis Pack</label>
                <select name="filling_machine_group_head_id" id="filling_machine_group_head_id" class="form-control">
                    <option value="id" selected disabled>-- Pilih Jenis Pack -- </option>
                    @foreach ($filling_machine_group_heads as $filling_machine_group_head)
                        <option value="{{ $filling_machine_group_head->encrypt_id }}">{{ $filling_machine_group_head->filling_machine_group_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="expired_range">Expired Range (dalam bulan) </label>
                <input required="true" autocomplete="off" type="text" name="expired_range" class="form-control" id="expired_range" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode != 47" maxlength="2">
            </div>
            <div class="form-group">
                <label for="trial_code">Trial Code </label>
                <input required="true" autocomplete="off" type="text" name="trial_code" class="form-control" id="trial_code">
            </div>
            <div class="form-group">
                <label for="sla">SLA (dalam hari) </label>
                <input required="true" autocomplete="off" type="text" name="sla" class="form-control" id="sla" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode != 47" maxlength="2">
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label for="spek_ts_min">Spek TS Min </label>
                <input required="true" autocomplete="off" type="text" name="spek_ts_min" class="form-control" id="spek_ts_min" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode != 47 " maxlength="5">
            </div>
            <div class="form-group">
                <label for="spek_ts_max">Spek TS Max </label>
                <input required="true" autocomplete="off" type="text" name="spek_ts_max" class="form-control" id="spek_ts_max" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode != 47 " maxlength="5">
            </div>
            <div class="form-group">
                <label for="spek_ph_min">Spek pH Min </label>
                <input required="true" autocomplete="off" type="text" name="spek_ph_min" class="form-control" id="spek_ph_min" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode != 47" maxlength="5">
            </div>
            <div class="form-group">
                <label for="spek_ph_max">Spek pH Max </label>
                <input required="true" autocomplete="off" type="text" name="spek_ph_max" class="form-control" id="spek_ph_max" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode != 47" maxlength="5">
            </div>
            <div class="form-group">
                <label for="waktu_analisa_mikro">Waktu Analisa Mikro (dalam hari) </label>
                <input required="true" autocomplete="off" type="text" name="waktu_analisa_mikro" class="form-control" id="waktu_analisa_mikro" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode != 47" maxlength="2">
            </div>

            <div class="form-group">
                <label for="inkubasi">Waktu Inkubasi (dalam hari) </label>
                <input required="true" autocomplete="off" type="text" name="inkubasi" class="form-control" id="inkubasi" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode != 47" maxlength="2">
            </div>

            <div class="form-group">
                <label for="is_active">Status Produk</label>
                <select name="is_active" id="is_active" class="form-control">
                    <option value="id" selected disabled>-- Pilih Status Produk -- </option>
                    <option value="1" >Active</option>
                    <option value="0" >Inactive</option>
                </select>
            </div>
            <div class="form-grup">
                <label for="action">&nbsp;</label>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                        <a class="btn btn-outline-secondary form-control text-black" data-dismiss="modal" onclick="resetModalSize()">Cancel</a>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                        <a class="btn btn-primary form-control text-white" onclick="addProduct()">Submit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function addProduct()
    {
        data    = $('#add-product-form').serialize();
        $.ajax({
            url:"{{url('master-apps/manage-product/add-new-product')}}",
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
