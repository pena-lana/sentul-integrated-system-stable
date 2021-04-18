<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <form method="post" id="add-menu-form">
            <div class="form-group">
                <label for="application_id">Application Name</label>
                <select name="application_id" id="application_id" class="form-control select2" onchange="changeApplication(this)">
                    <option value="0" selected disabled> Choose Application </option>
                    <?php $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($application->encrypt_id); ?>"><?php echo e($application->application_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label for="parent_menu" >Parent Menu</label>
                <select name="parent_menu" id="parent_menu" class="form-control select2" onchange="changeParentMenu(this)">
                </select>
            </div>
            <div class="form-group">
                <label for="menu_name">Menu Name</label>
                <input type="text" name="menu_name" id="menu_name" class="form-control" autocomplete="off" required>
            </div>
            <div class="form-group">
                <label for="menu_route">Menu Route</label>
                <input type="text" name="menu_route" id="menu_route" class="form-control" placeholder="Ex. master_app.home or for parent menu use - | connected to route name on application folder" required>
            </div>

            <div class="form-group">
                <label for="menu_icon">Menu Icon</label>
                <input type="text" name="menu_icon" id="menu_icon" class="form-control" placeholder="Ex. Fa-Home , use Font awesome 5" required>
            </div>
        </form>

        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                <button class="btn btn-outline-secondary form-control" data-dismiss="modal" onclick="resetModalSize()">Cancel</button>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                <button class="btn btn-primary form-control" onclick="addMenu()">Submit</button>
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
    function changeApplication(params)
    {
        application_id      = params.value;
        $.ajax({
            url:"<?php echo e(url('master-apps/manage-menu/change-application')); ?>",
            type:'POST',
            beforeSend:function()
            {
                $('.loading-bar').removeClass('hidden');
            },
            headers: {
                'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
            },
            data:{
                'application_id' : application_id
            },
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
                    default:
                        $('#parent_menu').html(data).on('change');
                    break;
                }
            },
            complete: function (data) {
                $('.loading-bar').addClass('hidden');
            }
        });
    }
    function addMenu()
    {
        data    = $('#add-menu-form').serialize();
        $.ajax({
            url:"<?php echo e(url('master-apps/manage-menu/add-new-menu')); ?>",
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/sentul-integrated-system-stable/resources/views/master_app/manage_menu/_form.blade.php ENDPATH**/ ?>