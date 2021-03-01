<form id="edit-schedule-form">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="form-group">
                <label for="product_id">Product Name</label>
                <select name="product_id" id="product_id" class="form-control select2">
                    @foreach ($products as $product)
                        <option value="{{ $product->encrypt_id }}" @if ($product->encrypt_id === $wo_number->encrypt_product_id) selected @endif>{{ $product->product_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="wo_number">Wo Number</label>
                <input type="hidden" name="encrypt_id" id="encrypt_id" class="form-control" autocomplete="off" placeholder="Wo Number" style="text-transform: uppercase" value="{{ $wo_number->encrypt_id }}" required>
                <input type="text" name="wo_number" id="wo_number" class="form-control" autocomplete="off" placeholder="Wo Number" style="text-transform: uppercase" value="{{ $wo_number->wo_number }}" required>
            </div>

            <div class="form-group">
                <label for="production_plan_date">Production Plan Date</label>
                <input type="date" name="production_plan_date" id="production_plan_date" class="form-control" autocomplete="off" placeholder="Production Plan Date" value="{{ $wo_number->production_plan_date }}" required>
            </div>
            <div class="form-group">
                <label for="plan_batch_size">Plan Batch Size (Kg)</label>
                <input type="text" name="plan_batch_size" id="plan_batch_size" class="form-control" autocomplete="off" placeholder="Plan Batch Size (Kg)" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode != 47" maxlength="8" value="{{ $wo_number->plan_batch_size }}" required>
            </div>

            <div class="form-group">
                <label for="plan_qty_box">Plan Qty (Box)</label>
                <input type="text" name="plan_qty_box" id="plan_qty_box" class="form-control" autocomplete="off" placeholder="Plan Qty (Box)" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode != 47" maxlength="8" value="{{ $wo_number->plan_qty_box }}" required>
            </div>
        </div>
    </div>
</form>
<hr>

<div class="row">
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
        <button class="btn btn-outline-secondary form-control" data-dismiss="modal" onclick="resetModalSize()">Cancel</button>
    </div>

    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
        <button class="btn btn-primary form-control" onclick="updateDraftSchedule()">Update</button>
    </div>
</div>

<script>
    $('.select2').select2({
            'theme':'bootstrap4'
    });
    function updateDraftSchedule()
    {
        data    = $('#edit-schedule-form').serialize();
        $.ajax({
            url:"{{url('rollie/production-schedule/update-draft-schedule')}}",
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
                        draft_table.ajax.reload(null,false);
                    break;

                    case '01':
                        Swal.fire({
                            title: 'Process Error ! ',
                            text: data.message,
                            icon: 'error'
                        });
                        $('#modal').modal('hide');
                        resetModalSize();
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
</script>
