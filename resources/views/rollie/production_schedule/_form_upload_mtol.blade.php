<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="form-group">
            <label for="mtol-file">Choose Mtol Excel File</label>
            <input type="file" class="form-control-file" id="mtol-file" style="border: 1px solid black;padding: 5px;" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" >
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
        <button class="btn btn-outline-secondary form-control" data-dismiss="modal" onclick="resetModalSize()">Cancel</button>
    </div>
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
        <button class="btn btn-primary form-control" onclick="uploadMtolFile()">
            Upload Mtol
        </button>
    </div>
</div>

<script>
    function uploadMtolFile()
    {
        var data = new FormData();
        var files = $('#mtol-file')[0].files;
        if (files.length)
        {
            data.append('mtol_file',files[0]);
            $.ajax({
                url:"{{url('rollie/production-schedule/upload-mtol')}}",
                type:'POST',
                beforeSend:function()
                {
                    $('.loading-bar').removeClass('hidden');
                },
                headers: {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                data:data,
                processData: false,
                contentType: false,
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
                        break;

                        case '02':
                            Swal.fire({
                                title: 'Process Error !',
                                text: data.message,
                                icon: 'error'
                            });
                            setTimeout(function(){ document.location.href='' }, 3000);
                        break;
                        case '00':
                            Swal.fire({
                                title: 'Process Success  ! ',
                                text: data.message,
                                icon: 'success'
                            });
                            $('#modal').modal('hide');
                            resetModalSize();
                            $('#button-finalize-draft').removeClass('hidden');
                            $('#button-back-to-dashboard').removeAttr('onclick');
                            $('#button-back-to-dashboard').attr('onclick','backToDashboard()');
                            draft_table.ajax.reload(null,false);
                        break;

                    }
                },
                complete: function (data) {
                    $('.loading-bar').addClass('hidden');
                }
            });
        }
        else
        {
            Swal.fire({
                title: 'Process Error ! ',
                text: "Harap pilih file mtol terlebih dahulu",
                icon: 'error'
            });
            $('#mtol-file').focus();
        }
    }
</script>
