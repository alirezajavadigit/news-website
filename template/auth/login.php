<!DOCTYPE html>
<html lang="en">

<?php require_once(BASE_PATH . '/template/auth/layouts/head-tag.php')?>


<body>

    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="<?= asset('public/auth/assets/images/img-01.png') ?>" alt="IMG">
                </div>

                <form method="post" action="<?= url('check-login') ?>" class="login100-form validate-form">
                    <span class="login100-form-title">
                        Register
                    </span>
                    <?php
                        $message = flash("login_error");
                        if(!empty($message)){
                    ?>
                    <div class="mb-2 alert alert-danger"> <small class="form-text text-danger"><?= $message ?></small> </div>
                    <?php } ?>

                    <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                        <input class="input100" type="text" name="email" placeholder="Email">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <input class="input100" type="password" name="password" placeholder="Password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button type="submit" class="login100-form-btn">
                            login
                        </button>
                    </div>

                    <div class="text-center p-t-12">
                        <span class="txt1">
                            Forgot
                        </span>
                        <a class="txt2" href="#">
                            Username / Password?
                        </a>
                    </div>

                    <div class="text-center p-t-136">
                        <a class="txt2" href="<?= url("register") ?>">
                            Login your Account
                            <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <?php require_once(BASE_PATH . '/template/auth/layouts/scripts.php')?>

  

</body>

</html>