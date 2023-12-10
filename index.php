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

require_once "database/DataBase.php";
require_once "activities/Admin/Admin.php";
require_once "activities/Admin/Category.php";
require_once "activities/Admin/Post.php";
/*
/   :)
/   project helpers started
/   :)
*/

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
/   (:
/   routes started
/   :)
*/

// category
uri("admin/category", 'Admin\Category', "index");
uri("admin/category/create", 'Admin\Category', "create");
uri("admin/category/store", 'Admin\Category', "store", "POST");
uri("admin/category/edit/{id}", 'Admin\Category', "edit");
uri("admin/category/update/{id}", 'Admin\Category', "update", "POST");
uri("admin/category/delete/{id}", 'Admin\Category', "delete");

// post
uri("admin/post", 'Admin\Post', "index");
uri("admin/post/create", 'Admin\Post', "create");
uri("admin/post/store", 'Admin\Post', "store", "POST");
uri("admin/post/edit/{id}", 'Admin\Post', "edit");
uri("admin/post/update/{id}", 'Admin\Post', "update", "POST");
uri("admin/post/delete/{id}", 'Admin\Post', "delete");



echo "404 - not found";


/*
/   (:
/   
/   :)
*/