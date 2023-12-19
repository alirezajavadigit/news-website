<?php

namespace Auth;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use database\DataBase;
use Exception;

class Auth
{
    protected $currentDomain;
    protected $basePath;

    function __construct()
    {
        $this->currentDomain = CURRENT_DOMAIN;
        $this->basePath = BASE_PATH;
    }

    protected function redirect($to)
    {
        $to = trim($this->currentDomain, "/ ") . "/" . trim($to, "/ ");
        header("Location: " . $to);
        exit();
    }

    protected function redirectBack()
    {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        exit();
    }

    protected function hash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    private function random()
    {
        return bin2hex(openssl_random_pseudo_bytes(32));
    }
    protected function activationMessage($username, $verifyToken)
    {
        $message = '<h1>فعال سازی حساب کاربری</h1>
                    <p> عزیز برای فعال سازی حساب کاربری خود لطفا روی لینک زیر کلیک نمایید ' . $username . ' </p>
                    <div><a href="' . url("register/activating/" . $verifyToken) . '">لینک فعال سازی</a></div>
        ';

        return $message;
    }
    protected function forgotMessage($username, $forgotToken)
    {
        $message = '<h1>فراموشی کلمه عبور</h1>
                    <p>   عزیز برای بازیابی کلمه عبور رویه لینک زیر کلیک کنید' . $username . '</p>
                    <div><a href="' . url("reset-password/" . $forgotToken) . '">لینک بازیابی</a></div>
        ';

        return $message;
    }
    protected function sendMail($emailAddress, $subject, $body)
    {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
            $mail->CharSet = "UTF-8"; //Enable verbose debug output
            $mail->isSMTP(); //Send using SMTP
            $mail->Host = MAIL_HOST; //Set the SMTP server to send through
            $mail->SMTPAuth = SMTP_AUTH; //Enable SMTP authentication
            $mail->Username = MAIL_USERNAME; //SMTP username
            $mail->Password = MAIL_PASSWORD; //SMTP password
            $mail->SMTPSecure = 'tls'; //Enable implicit TLS encryption
            $mail->Port = MAIL_PORT; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom(SENDER_MAIL, SENDER_NAME);
            $mail->addAddress($emailAddress);


            //Content
            $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();

            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }

    public function register()
    {
        return view("template.auth.register.php");
    }

    public function registerStore($request)
    {
        if (empty($request['email']) || empty($request['username']) || empty($request['password'])) {

            flash("register_error", "پر کردن همه فیلد ها اجباری است!");
            $this->redirectBack();
        } elseif (strlen($request['password']) < 8) {
            flash("register_error", "کلمه عبور شما کمتر از 8 کاراکتر است");
            $this->redirectBack();
        } elseif (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
            flash("register_error", "لطفا ایمیل معتبر وارد کنید");
            $this->redirectBack();
        } else {
            $db = new DataBase();
            $user = $db->select("SELECT * FROM users WHERE email = ?", [$request['email']])->fetch();
            if ($user != null) {
                $this->redirectBack();
            } else {
                $randomToken = $this->random();
                // $db->insert("users", )
                $activationMessage = $this->activationMessage($request['username'], $randomToken);
                $result = $this->sendMail($request['email'], "فعال سازی حساب کاربری", $activationMessage);
                if ($result) {
                    $request['verify_token'] = $randomToken;
                    $request['password'] = $this->hash($request['password']);
                    $db->insert("users", array_keys($request), $request);
                    $this->redirect("login");
                } else {
                    flash("register_error", "لطفا");

                    $this->redirectBack();
                }
            }
        }
    }

    public function activeUser($token)
    {
        $db = new DataBase();
        $user = $db->select("SELECT * FROM users WHERE verify_token = ? AND is_active = 0;", [$token])->fetch();
        if ($user != null) {
            $values = array();
            $values['is_active'] = 1;
            $db->update("users", $user['id'], array_keys($values), $values);
            return view("template.auth.activation.php");
        } else {
            return view("template.error.wrong-activate-token.php");
        }
    }

    public function login()
    {
        return view("template.auth.login.php");
    }

    public function checkLogin($request)
    {
        if (empty($request['email']) || empty($request['password'])) {
            flash("login_error", "پر کردن تمامی فیلد ها الزامی میباشد");
            return $this->redirectBack();
        }
        $db = new DataBase();
        $user = $db->select("SELECT * FROM users WHERE email = ? AND is_active = 1;", [$request['email']])->fetch();
        if ($user == null) {
            flash("login_error", "کاربر وجود ندارد ثبت نام کنید یا اگر ثبت نام کردید ایمیل خود را تایید کنید");
            return $this->redirectBack();
        }

        if (password_verify($request['password'], $user['password'])) {
            $_SESSION['user'] = $user['id'];
            return $this->redirect("admin");
        } else {
            flash("login_error", "رمز عبور یا ایمیل شما اشتباه است");
            return $this->redirectBack();
        }
    }

    public function logout()
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
            return $this->redirect("home");
        } else {
            return $this->redirect("home");
        }
    }

    public function isLogin()
    {
        if (isset($_SESSION['user'])) {
            return true;
        } else {
            return false;
        }
    }

    public function forgot()
    {
        return view("template.auth.forgot.php");
    }
    public function forgotSendEmail($request)
    {
        if (empty($request['email'])) {
            flash("forgot_email_erros", "لطفا فیلد ایمیل را پر کنید");
            return $this->redirectBack();
        } else {
            $db = new DataBase();
            $user = $db->select("SELECT * FROM users WHERE email = ? AND is_active = 1;", [$request['email']])->fetch();
            if($user == null){
                flash("forgot_email_erros", "ایمیل وارد شده در سیستم موجود نمیباشد");
                return $this->redirectBack();
            }
            $forgotToken = $this->random();
            $forgotMessage = $this->forgotMessage($user['username'], $forgotToken);
            $result = $this->sendMail($request['email'], "بازیابی کلمه عبور", $forgotMessage);
            if($result){
                flash("forgot_email_erros", "ok");
                $values = array();
                $values['forgot_token'] = $forgotToken;
                $values['forgot_token_expire'] = date("Y-m-d H:i:s", strtotime("+15 minutes"));
                $db->update("users", $user['id'], array_keys($values), $values);
                return $this->redirect("login");
            }else{
                flash("forgot_email_erros", "عملیات با خطا مواجه شد");
                return $this->redirectBack();
            }

        }
    }
    public function resetPassword($token)
    {
        return view("template.auth.reset-password.php", compact("token"));
        
    }
    public function changePassword($request, $token)
    {
        $db = new DataBase();
        $user = $db->select("SELECT * FROM users WHERE forgot_token = ?", [$token])->fetch();
        if($user == null){
            flash("forgot_email_erros", "عملیات با خطا مواجه شد");
            return $this->redirectBack();
        }
        $values = array();
        $values['password'] = $this->hash($request['password']);
        $db->update("users", $user['id'], array_keys($values), $values);
        return $this->redirect("login");
    }
}
