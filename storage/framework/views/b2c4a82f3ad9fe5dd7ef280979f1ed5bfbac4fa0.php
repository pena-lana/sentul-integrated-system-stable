<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <form method="post" id="edit-application-form">
            <div class="form-group">
                <label for="application_name">Application Name</label>
                <input type="text" name="application_name" id="application_name" value="<?php echo e($application->application_name); ?>" class="form-control">
                <input type="hidden" name="application_id" id="application_id" value="<?php echo e($application->encrypt_id); ?>" class="form-control">
            </div>

            <div class="form-group">
                <label for="application_description">Application Description</label>
                <textarea type="text" name="application_description" id="application_description" class="form-control"><?php echo e($application->application_description); ?></textarea>
            </div>
            <div class="form-group">
                <label for="application_link">Application Link</label>
                <input type="text" name="application_link" id="application_link" value="<?php echo e($application->application_link); ?>" class="form-control">
            </div>
        </form>

        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                <button class="btn btn-outline-secondary form-control" data-dismiss="modal" onclick="resetModalSize()">Cancel</button>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                <button class="btn btn-primary form-control" onclick="updateApplication()">Update</button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/sentul-integrated-system-stable/resources/views/masterapp/manage_application/edit.blade.php ENDPATH**/ ?>