<?php 
use database\DataBase;
/*
/   (:
/   sesstion started here
/   :)
*/

session_start();


/*
/   (:
/   general config
/   :)
*/

define('BASE_PATH', __DIR__);

define('CURRENT_DOMAIN', currentDomain() . '/project/');

define('DISPLAY_ERROR', true);

/*
/   :)
/   database config  
/   :)
*/

define('DB_HOST', 'localhost');
define('DB_NAME', 'php-project');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');

/*
/   :)
/   mail config  
/   :)
*/
define('MAIL_HOST', 'smtp.gmail.com');
define('SMTP_AUTH', true);
define('MAIL_USERNAME', 'dota2programer@gmail.com');
define('MAIL_PASSWORD', 'ayqc tjpf miki zdgm');
define('MAIL_PORT', 587);
define('SENDER_MAIL', 'dota2programer@gmail.com');
define('SENDER_NAME', 'News Website');

/*
/   :)
/   admin controllers required  
/   :)
*/
require_once "database/DataBase.php";
require_once "activities/Admin/Admin.php";
require_once "activities/Admin/Category.php";
require_once "activities/Admin/Post.php";
require_once "activities/Admin/Banner.php";
require_once "activities/Admin/User.php";
require_once "activities/Admin/Comment.php";
require_once "activities/Admin/Menu.php";
require_once "activities/Admin/Setting.php";

/*
/   :)
/   auth controllers required  
/   :)
*/
require_once "activities/Auth/Auth.php";

/*
/   :)
/   app controllers required  
/   :)
*/
require_once "activities/App/Home.php";


spl_autoload_register(function($className){
    $path = BASE_PATH . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR;
    include $path . $className . ".php";
});

/*
/   :)
/   project helpers started
/   :)
*/

function jalaliDate($date){
    return \Parsidev\Jalali\jDate::forge($date)->format("datetime");
}
function view($src, $variables = null){
    if($variables){

        foreach($variables as $key => $variable){
            $$key = $variable;
        }
    }
    $src = explode(".", $src);
    $end = end($src);
    array_pop($src);
    $src = implode("/", $src) . "." . $end;
    require_once(trim(BASE_PATH , "/ ") . "/" . trim($src, "/ "));
}
function uri($reservedUrl, $class, $method, $requestMethod = "GET"){

    // current Url
    $currentUrl =   explode("?", currentUrl())[0];
    $currentUrl = str_replace(CURRENT_DOMAIN, '', $currentUrl);
    $currentUrl = trim($currentUrl,'/ ');
    $currentUrlArray = explode("/", $currentUrl);
    $currentUrlArray = array_filter($currentUrlArray);
    
    // reserved Url

    $reservedUrl = trim($reservedUrl, "/ ");
    $reservedUrlArray = explode("/", $reservedUrl);
    $reservedUrlArray = array_filter($reservedUrlArray);     
    if(sizeof($currentUrlArray) != sizeof($reservedUrlArray) || methodField() != $requestMethod){
        return false;
    } 
    $parameters = [];       
    for($key = 0; $key < sizeof($reservedUrlArray); $key++){
        if($reservedUrlArray[$key][0] == "{" && $reservedUrlArray[$key][strlen($reservedUrlArray[$key]) - 1] == "}"){
            array_push($parameters, $currentUrlArray[$key]);
        }elseif($currentUrlArray[$key] !== $reservedUrlArray[$key]){
            return false;
        }

    }
    if(methodField() == "POST"){
        $request = isset($_FILES) ? array_merge($_POST, $_FILES) : $_POST;
        $parameters = array_merge([$request], $parameters);
    }
    $object = new $class;
    call_user_func_array(array($object, $method), $parameters);
    exit();
}
function protocol(){
    return stripos($_SERVER['SERVER_PROTOCOL'], "https") === true ? "https://" : "http://";
}

function currentDomain(){
    return protocol() . $_SERVER['HTTP_HOST'];
}

function asset($src){
    $domain = trim(CURRENT_DOMAIN, "/ ");
    return $domain . "/" . trim($src, "/ ");
}
function url($url){
    $domain = trim(CURRENT_DOMAIN, "/ ");
    return $domain . "/" . trim($url, "/ ");
}

function currentUrl(){
    return currentDomain() . $_SERVER['REQUEST_URI'];
}

function methodField(){
    return $_SERVER['REQUEST_METHOD'];
}

function displayError($displayError){
    if($displayError){
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }else{
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(0);

    }
}

displayError(DISPLAY_ERROR);

global $flashMessage;
if(isset($_SESSION['flash_message'])){
    $flashMessage = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}
function flash($name, $value = null){
    if($value === null){
        global $flashMessage;
        $message = isset($flashMessage[$name]) ? $flashMessage[$name] : '';
        return $message;
    }else{
        $_SESSION['flash_message'][$name] = $value;
        return true;   
    }
}

function dd($var){
    echo "<pre>";
    var_dump($var);
    exit;
}
/*
/   :)
/   project setting information helper
/   :)
*/
function setting(){
    $db = new DataBase;
    return $db->select("SELECT * FROM setting")->fetch();
}
spl_autoload_register(function($className){
    $path = BASE_PATH . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR;
    include $path . $className . ".php";
});

/*
/   (:
/   routes started
/   :)
*/
/*
/   (:
/   admin routes started
/   :)
*/
uri("admin", 'Admin\Admin', "index");
/*
/   (:
/   category
/   :)
*/
uri("admin/category", 'Admin\Category', "index");
uri("admin/category/create", 'Admin\Category', "create");
uri("admin/category/store", 'Admin\Category', "store", "POST");
uri("admin/category/edit/{id}", 'Admin\Category', "edit");
uri("admin/category/update/{id}", 'Admin\Category', "update", "POST");
uri("admin/category/delete/{id}", 'Admin\Category', "delete");

/*
/   (:
/   post
/   :)
*/
uri("admin/post", 'Admin\Post', "index");
uri("admin/post/create", 'Admin\Post', "create");
uri("admin/post/store", 'Admin\Post', "store", "POST");
uri("admin/post/edit/{id}", 'Admin\Post', "edit");
uri("admin/post/update/{id}", 'Admin\Post', "update", "POST");
uri("admin/post/delete/{id}", 'Admin\Post', "delete");
/*
/   (:
/   post change status
/   :)
*/
uri("admin/post/breakingNews/{id}", 'Admin\Post', "breakingNewsChangeStatus");
uri("admin/post/selected/{id}", 'Admin\Post', "selectedChangeStatus");
/*
/   (:
/   banners
/   :)
*/
uri("admin/banner", 'Admin\Banner', "index");
uri("admin/banner/create", 'Admin\Banner', "create");
uri("admin/banner/store", 'Admin\Banner', "store", "POST");
uri("admin/banner/edit/{id}", 'Admin\Banner', "edit");
uri("admin/banner/update/{id}", 'Admin\Banner', "update", "POST");
uri("admin/banner/delete/{id}", 'Admin\Banner', "delete");
/*
/   (:
/   users
/   :)
*/
uri("admin/user", 'Admin\User', "index");
uri("admin/user/edit/{id}", 'Admin\User', "edit");
uri("admin/user/update/{id}", 'Admin\User', "update", "POST");
uri("admin/user/delete/{id}", 'Admin\User', "delete");
uri("admin/user/permission-change/{id}", 'Admin\User', "changePermission");
/*
/   (:
/   comments
/   :)
*/
uri("admin/comment", 'Admin\Comment', "index");
uri("admin/comment/show/{id}", 'Admin\Comment', "show");
uri("admin/comment/approve-toggle/{id}", 'Admin\Comment', "approveToggle");
/*
/   (:
/   menus
/   :)
*/
uri("admin/menu", 'Admin\Menu', "index");
uri("admin/menu/create", 'Admin\Menu', "create");
uri("admin/menu/store", 'Admin\Menu', "store", "POST");
uri("admin/menu/edit/{id}", 'Admin\Menu', "edit");
uri("admin/menu/update/{id}", 'Admin\Menu', "update", "POST");
uri("admin/menu/delete/{id}", 'Admin\Menu', "delete");
/*
/   (:
/   setting
/   :)
*/
uri("admin/setting", 'Admin\Setting', "index");
uri("admin/setting/show", 'Admin\Setting', "show");
uri("admin/setting/update", 'Admin\Setting', "update", "POST");


/*
/   (:
/   register routes
/   :)
*/
uri('register', 'Auth\Auth', 'register');
uri('register/store', 'Auth\Auth', 'registerStore', 'POST');
uri('register/activating/{token}', 'Auth\Auth', 'activeUser');
uri('login', 'Auth\Auth', 'login');
uri('check-login', 'Auth\Auth', 'checkLogin', 'POST');
uri('forgot', 'Auth\Auth', 'forgot');
uri('forgot/send-email', 'Auth\Auth', 'forgotSendEmail', 'POST');
uri('reset-password/{token}', 'Auth\Auth', 'resetPassword');
uri('change-password/{token}', 'Auth\Auth', 'changePassword', "POST");
uri('logout', 'Auth\Auth', 'logout');

/*
/   (:
/   app routes
/   :)
*/
uri('/', 'App\Home', 'index');
uri('home', 'App\Home', 'index');
uri('show/category/{id}', 'App\Home', 'categoryShow');
uri('show/post/{id}', 'App\Home', 'postShow');
uri('comment/store', 'App\Home', 'commentStore', "POST");


/*
/   (:
/   404 not found page
/   :)
*/

require_once(BASE_PATH . "/template/error/404.html");


