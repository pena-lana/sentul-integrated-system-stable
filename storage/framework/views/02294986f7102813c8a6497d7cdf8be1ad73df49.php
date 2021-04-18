<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <form method="post" id="edit-menu-form">
            <div class="form-group">
                <label for="application_name">Application Name</label>
                <input type="text" name="application_name" id="application_name" value="<?php echo e($menu->application->application_name); ?>" class="form-control" readonly>
                <input type="hidden" name="application_id" id="application_id" value="<?php echo e($menu->application->encrypt_id); ?>">
            </div>
            <div class="form-group">
                <label for="parent_menu">Parent Menu</label>
                <select name="parent_id" id="parent_id" class="form-control select2">
                    <?php echo $menus; ?>

                </select>
            </div>
            <div class="form-group">
                <label for="menu_name">Menu Name</label>
                <input type="text" name="menu_name" id="menu_name" value="<?php echo e($menu->menu_name); ?>" class="form-control">
                <input type="hidden" name="menu_id" id="menu_id" value="<?php echo e($menu->encrypt_id); ?>" class="form-control">
            </div>

            <div class="form-group">
                <label for="menu_route">Menu Route</label>
                <input type="text" name="menu_route" id="menu_route" value="<?php echo e($menu->menu_route); ?>" class="form-control">

            </div>
            <div class="form-group">
                <label for="menu_icon">menu icon</label>
                <input type="text" name="menu_icon" id="menu_icon" value="<?php echo e($menu->menu_icon); ?>" class="form-control">
            </div>
        </form>

        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                <button class="btn btn-outline-secondary form-control" data-dismiss="modal" onclick="resetModalSize()">Cancel</button>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                <button class="btn btn-primary form-control" onclick="updateMenu()">Update</button>
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
    function updateMenu()
    {
        data    = $('#edit-menu-form').serialize();
        $.ajax({
            url:"<?php echo e(url('master-apps/manage-menu/update-menu')); ?>",
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/sentul-integrated-system-stable/resources/views/masterapp/manage_menu/_edit.blade.php ENDPATH**/ ?>