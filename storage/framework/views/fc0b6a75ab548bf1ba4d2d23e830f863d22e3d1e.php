<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card  card-outline">
                <div class="card-header bg-dark">
                    Application Permissions
                    <div class="float-right <?php echo e(Session::get('create')); ?>">
                        <button class="btn btn-outline-primary text-white" onclick="modalAddMenu()">
                            <i class="fas fa-plus"></i>&nbsp;&nbsp;New Menu
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered" id="menu-table" style="min-width: 100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="hidden">cust id</th>
                                <th>Application Name</th>
                                <th>Menu Name</th>
                                <th>Menu Route</th>
                                <th>Menu Position</th>
                                <th>Status</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th class="hidden"></th>
                                <th class="search-col">Application Name</th>
                                <th ></th>
                                <th ></th>
                                <th ></th>
                                <th ></th>
                                <th ></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/sentul-integrated-system-stable/resources/views/master_app/application_permissions/index.blade.php ENDPATH**/ ?>