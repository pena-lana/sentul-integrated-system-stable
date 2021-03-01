<form id="edit-schedule-form">
    <div class="row">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="form-group">
                <label for="product_id">Product Name</label>
                <input type="text" name="product_id" id="product_id" value="{{ $wo_number->product->product_name }}" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="wo_number">Wo Number</label>
                <input type="hidden" name="encrypt_id" id="encrypt_id" class="form-control" autocomplete="off" placeholder="Wo Number" style="text-transform: uppercase" value="{{ $wo_number->encrypt_id }}" required>
                <input type="text" name="wo_number" id="wo_number" class="form-control" autocomplete="off" placeholder="Wo Number" style="text-transform: uppercase" value="{{ $wo_number->wo_number }}" required>
            </div>

            <div class="form-group">
                <label for="production_plan_date">Production Plan Date</label>
                <input type="date" name="production_plan_date" id="production_plan_date" class="form-control" autocomplete="off" placeholder="Production Plan Date" value="{{ $wo_number->production_plan_date }}" @if ($wo_number->production_realisation_date !== '' && !is_null($wo_number->production_realisation_date)) readonly @endif required>
            </div>
            <div class="form-group">
                <label for="production_realisation_date">Production Realisation Date</label>
                <input type="date" name="production_realisation_date" id="production_realisation_date" class="form-control" autocomplete="off" placeholder="Production Realisation Date" value="{{ $wo_number->production_realisation_date }}" @if ($wo_number->production_realisation_date !== '' && !is_null($wo_number->production_realisation_date)) readonly @endif required>
            </div>
            <div class="form-group">
                <label for="actual_batch_size">Actual Batch Size (Kg)</label>
                <input type="text" name="actual_batch_size" id="actual_batch_size" class="form-control" autocomplete="off" placeholder="Plan Batch Size (Kg)" onkeypress="return event.charCode >= 46 && event.charCode <= 57 && event.charCode != 47" maxlength="8" value="{{ $wo_number->actual_batch_size }}" required>
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
        <button class="btn btn-primary form-control" onclick="updateSchedule()">Update</button>
    </div>
</div>

<script>
    $('.select2').select2({
            'theme':'bootstrap4'
    });
    function updateSchedule()
    {
        data    = $('#edit-schedule-form').serialize();
        $.ajax({
            url:"{{url('rollie/production-schedule/update-schedule')}}",
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
                        $('#modal').modal('hide');
                        resetModalSize();
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
</script>
