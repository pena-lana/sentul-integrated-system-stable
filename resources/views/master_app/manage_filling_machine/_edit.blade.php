<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <form method="post" id="update-filling-machine-form">
            <div class="form-group">
                <label for="filling_machine_name">Filling Machine Name</label>
                <input type="hidden" name="encrypt_id" id="encrypt_id" class="form-control" placeholder="ex. TBA / A3" value="{{ $filling_machine->encrypt_id }}" required>
                <input type="text" name="filling_machine_name" id="filling_machine_name" class="form-control" placeholder="ex. TBA / A3" value="{{ $filling_machine->filling_machine_name }}" required>
            </div>

            <div class="form-group">
                <label for="filling_machine_code">Filling Machine Code</label>
                <input type="text" name="filling_machine_code" id="filling_machine_code" class="form-control" placeholder="Ex. TBA C / A3CF B" value="{{ $filling_machine->filling_machine_code }}" required>
            </div>
        </form>

        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                <button class="btn btn-outline-secondary form-control" data-dismiss="modal" onclick="resetModalSize()">Cancel</button>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                <button class="btn btn-primary form-control" onclick="updateFillingMachine()">Update</button>
            </div>
        </div>
    </div>
</div>

<script>
    function updateFillingMachine()
    {
        data    = $('#update-filling-machine-form').serialize();
        $.ajax({
            url:"{{url('master-apps/manage-filling-machine/update-filling-machine')}}",
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
