<?php $__env->startSection('active-home'); ?>
    active
<?php $__env->stopSection(); ?>
<?php $__env->startSection('title-content'); ?>
    Choose Application
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <?php if($application_access > 0): ?>
                <?php $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-primary card-outline" style="min-height: 270px">
                            <div class="card-header text-center">
                                <h5 class="card-title m-0 text-center">
                                    <?php echo e($application->application_name); ?>

                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <p class="card-text text-justify">
                                                <?php echo e($application->application_description); ?>

                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="<?php echo e($application->application_link); ?>" class="btn btn-primary form-control">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>

            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.facepage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/sentul-integrated-system-stable/resources/views/home.blade.php ENDPATH**/ ?>