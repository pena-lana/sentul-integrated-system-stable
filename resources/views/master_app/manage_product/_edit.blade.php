<form method="post" id="edit-product-form">
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label for="product_name">Nama Produk</label>
                <input required="true" autocomplete="off" type="text" class="form-control" id="product_name" name="product_name" value="{{ $product->product_name }}">
                <input required="true" autocomplete="off" type="hidden" class="form-control" id="encrypt_id" name="encrypt_id" value="{{ $product->encrypt_id }}">
            </div>
            <div class="form-group">
                <label for="oracle_code">Kode Oracle</label>
                <input required="true" autocomplete="off" type="text" class="form-control" id="oracle_code" name="oracle_code" value="{{ $product->oracle_code }}">
            </div>
            <div class="form-group">
                <label for="subbrand_id">Brand</label>
                <select name="subbrand_id" id="subbrand_id" class="form-control">
                    <option value="id" selected disabled>-- Pilih Brand -- </option>
                    @foreach ($subbrands as $subbrand)
                        <option value="{{ $subbrand->encrypt_id }}" <?php if($subbrand->encrypt_id == $product->encrypt_subbrand_id){ echo "selected"; }?>>{{ $subbrand->subbrand_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="product_type_id">Jenis Produk</label>
                <select name="product_type_id" id="product_type_id" class="form-control">
                    <option value="id" selected disabled>-- Pilih Jenis Produk -- </option>
                    @foreach ($product_types as $product_type)
                        <option value="{{ $product_type->encrypt_id }}"  <?php if($product_type->encrypt_id == $product->encrypt_product_type_id){ echo "selected"; }?>>{{ $product_type->product_type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="filling_machine_group_head_id">Jenis Pack</label>
                <select name="filling_machine_group_head_id" id="filling_machine_group_head_id" class="form-control">
                    <option value="id" selected disabled>-- Pilih Jenis Pack -- </option>
                    @foreach ($filling_machine_group_heads as $filling_machine_group_head)
                        <option value="{{ $filling_machine_group_head->encrypt_id }}" <?php if($filling_machine_group_head->encrypt_id == $product->encrypt_filling_machine_group_head_id){ echo "selected"; }?>>{{ $filling_machine_group_head->filling_machine_group_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="expired_range">Expired Range (dalam bulan) </label>
                <input required="true" autocomplete="off" type="text" name="expired_range" class="form-control" id="expired_range" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode != 47" maxlength="2" value="{{ $product->expired_range }}">
            </div>
            <div class="form-group">
                <label for="trial_code">Trial Code </label>
                <input required="true" autocomplete="off" type="text" name="trial_code" class="form-control" id="trial_code" value="{{ $product->trial_code }}">
            </div>
            <div class="form-group">
                <label for="sla">SLA (dalam hari) </label>
                <input required="true" autocomplete="off" type="text" name="sla" class="form-control" id="sla" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode != 47" maxlength="2" value="{{ $product->sla }}">
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label for="spek_ts_min">Spek TS Min </label>
                <input required="true" autocomplete="off" type="text" name="spek_ts_min" class="form-control" id="spek_ts_min" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode != 47 " maxlength="5" value="{{ $product->spek_ts_min }}">
            </div>
            <div class="form-group">
                <label for="spek_ts_max">Spek TS Max </label>
                <input required="true" autocomplete="off" type="text" name="spek_ts_max" class="form-control" id="spek_ts_max" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode != 47 " maxlength="5" value="{{ $product->spek_ts_max }}">
            </div>
            <div class="form-group">
                <label for="spek_ph_min">Spek pH Min </label>
                <input required="true" autocomplete="off" type="text" name="spek_ph_min" class="form-control" id="spek_ph_min" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode != 47" maxlength="5" value="{{ $product->spek_ph_min }}">
            </div>
            <div class="form-group">
                <label for="spek_ph_max">Spek pH Max </label>
                <input required="true" autocomplete="off" type="text" name="spek_ph_max" class="form-control" id="spek_ph_max" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode != 47" maxlength="5" value="{{ $product->spek_ph_max }}">
            </div>
            <div class="form-group">
                <label for="waktu_analisa_mikro">Waktu Analisa Mikro (dalam hari) </label>
                <input required="true" autocomplete="off" type="text" name="waktu_analisa_mikro" class="form-control" id="waktu_analisa_mikro" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode != 47" maxlength="2" value="{{ $product->waktu_analisa_mikro }}">
            </div>

            <div class="form-group">
                <label for="inkubasi">Waktu Inkubasi (dalam hari) </label>
                <input required="true" autocomplete="off" type="text" name="inkubasi" class="form-control" id="inkubasi" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode != 47" maxlength="2" value="{{ $product->inkubasi }}">
            </div>

            <div class="form-group">
                <label for="is_active">Status Produk</label>
                <select name="is_active" id="is_active" class="form-control">
                    <option value="id" selected disabled>-- Pilih Status Produk -- </option>
                    <option value="1" @if ($product->is_active == '1') selected @endif>Active</option>
                    <option value="0" @if ($product->is_active == '0') selected @endif>Inactive</option>
                </select>
            </div>
            <div class="form-grup">
                <label for="action">&nbsp;</label>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                        <a class="btn btn-outline-secondary form-control text-black" data-dismiss="modal" onclick="resetModalSize()">Cancel</a>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                        <a class="btn btn-primary form-control text-white" onclick="editProduct()">Submit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function editProduct()
    {
        data    = $('#edit-product-form').serialize();
        $.ajax({
            url:"{{url('master-apps/manage-product/edit-product')}}",
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
