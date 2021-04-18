<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <form method="post" id="add-filling-machine-detail-form">
            <div class="form-group">
                <label for="filling_machine">Filling Machine</label>
                <input type="hidden" name="encrypt_id" id="encrypt_id" class="form-control" placeholder="ex. TBA / A3" value="<?php echo e($filling_machine_group_head->encrypt_id); ?>" required>
                <select name="filling_machine[]" id="filling_machine[]" class="select2 form-control" placeholder="Choose Min One Filling Machine" multiple required>
                    <?php $__currentLoopData = $filling_machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filling_machine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($filling_machine->encrypt_id); ?>"><?php echo e($filling_machine->filling_machine_code); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </form>

        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                <button class="btn btn-outline-secondary form-control" data-dismiss="modal" onclick="resetModalSize()">Cancel</button>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                <button class="btn btn-primary form-control" onclick="addFillingMachineGroupDetail()">Add To Filling Machine Group <?php echo e($filling_machine_group_head->filling_machine_group_name); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function()
    {
        $('.select2').select2({
                'theme':'bootstrap4'
        });
    });
    function addFillingMachineGroupDetail()
    {
        data    = $('#add-filling-machine-detail-form').serialize();
        $.ajax({
            url:"<?php echo e(url('master-apps/manage-filling-machine-group/add-filling-machine-group-detail')); ?>",
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/sentul-integrated-system-stable/resources/views/master_app/manage_filling_machine_group/_form_add_filling_machine_group_detail.blade.php ENDPATH**/ ?>