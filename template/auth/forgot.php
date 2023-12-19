<!DOCTYPE html>
<html lang="en">
<head>
    <title>فراموشی رمز عبور</title>
<?php require_once(BASE_PATH . '/template/auth/layouts/head-tag.php')?>
</head>

<body>

<div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                <img src="<?= asset('public/auth/assets/images/img-01.png') ?>" alt="IMG">
                </div>

                <form method="post" action="<?= url('forgot/send-email') ?>" class="login100-form validate-form">
                    <span class="login100-form-title">
                        Forgot Password
                    </span>

                    <?php
                        $message = flash('forgot_email_erros');
                        if (!empty($message)) {
                            ?>

                    <div class="mb-2 alert alert-danger"> <small class="form-text text-danger">
                        <?= $message ?>
                    </small> </div>

                    <?php
                        } ?>


                    <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                        <input class="input100" type="text" name="email" placeholder="Email">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>


                    <div class="container-login100-form-btn">
                        <button type="submit" class="login100-form-btn">
                            Send
                        </button>
                    </div>



                    <div class="text-center p-t-136">
                        <a class="txt2" href="<?= url("register") ?>">
                            Create your Account
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