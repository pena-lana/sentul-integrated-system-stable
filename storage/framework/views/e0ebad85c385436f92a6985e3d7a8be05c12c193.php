<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo e(mix('css/admin-lte.css')); ?>">
</head>
<body class="login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="javascript:void(0)" class="h1">
                    <img src="<?php echo e(asset('login-page/images/logo.png')); ?>" alt="login-logo" class="img-fluid">
                </a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your journey</p>
                <form action="login" method="post">
                    <?php echo e(csrf_field()); ?>

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Username" name="username" autocomplete="off" required/>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" autocomplete="off" required/>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <!-- /.social-auth-links -->
                <p class="mb-1 mt-3 ">
                    <a href="forgot-password.html">I forgot my password</a>
                </p>
                <p class="mb-0">
                    <a href="register.html" class="text-center">Register a new account</a>
                </p>
            </div>
            <div class="card-footer bg-primary">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center">
                            Design with <i class="fas fa-heart"></i> by <a href="https://www.instagram.com/nesta_nm" style="color:#c7ff00;">Nesta Maulana</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <?php if($message = Session::get('success')): ?>
        <div class="success" data-flashdata="<?php echo e($message); ?>"></div>
    <?php endif; ?>

    <?php if($message = Session::get('error')): ?>
        <div class="error" data-flashdata="<?php echo e($message); ?>"></div>
    <?php endif; ?>

    <?php if($message = Session::get('infonya')): ?>
        <div class="infonya" data-flashdata="<?php echo e($message); ?>"></div>
    <?php endif; ?>
    <script src="<?php echo e(mix('js/admin-lte.js')); ?>"></script>
    <script>
        $(document).ready(function ()
        {
            const error_mess = $('.error').data('flashdata');
            if (error_mess)
            {
                Swal.fire({
                    title: 'Proses Gagal!',
                    text: error_mess,
                    icon: 'error'
                })
            }
            const success_mess = $('.success_mess').data('flashdata');
            if (success_mess) {
                Swal.fire({
                    title: 'Proses Gagal!',
                    text: success,
                    icon: 'success'
                })
            }

            const info_mess = $('.info').data('flashdata');
            if(info_mess){
                Swal.fire({
                    title: 'Proses Gagal!',
                    text: info_mess,
                    icon: 'info'
                })
            }
        });

    </script>
</body>
</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/sentul-integrated-system-stable/resources/views/auth/login.blade.php ENDPATH**/ ?>