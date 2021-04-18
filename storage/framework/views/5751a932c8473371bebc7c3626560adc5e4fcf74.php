<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <form method="post" id="add-application-form">
            <div class="form-group">
                <label for="application_name">Application Name</label>
                <input type="text" name="application_name" id="application_name" class="form-control" placeholder="Ex. Master App / Rollie" required>
            </div>

            <div class="form-group">
                <label for="application_description">Application Description</label>
                <textarea type="text" name="application_description" id="application_description" class="form-control" placeholder="Ex. Aplikasi untuk mengelola data proses produksi " required></textarea>
            </div>
            <div class="form-group">
                <label for="application_link">Application Link</label>
                <input type="text" name="application_link" id="application_link" class="form-control" placeholder="Ex. master-app / rollie | connected to route on application folder" required>
            </div>
            <div class="form-group">
                <label for="is_active">Status Application</label>
                <select name="is_active" id="is_active" class="select form-control" required>
                    <option value="2" disabled selected>Select Status Application</option>
                    <option value="0">Inactive</option>
                    <option value="1">Active</option>
                </select>
            </div>
        </form>

        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                <button class="btn btn-outline-secondary form-control" data-dismiss="modal" onclick="resetModalSize()">Cancel</button>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                <button class="btn btn-primary form-control" onclick="addApplication()">Submit</button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/sentul-integrated-system-stable/resources/views/masterapp/manage_application/form.blade.php ENDPATH**/ ?>