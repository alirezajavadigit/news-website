<?php

namespace Admin;

use Auth\Auth;
use database\DataBase;

class Admin
{
    protected $currentDomain;
    protected $basePath;

    function __construct()
    {
        $this->currentDomain = CURRENT_DOMAIN;
        $this->basePath = BASE_PATH;
        $auth = new Auth;
        $db = new DataBase();
        if ($auth->isLogin()) {

            $user = $db->select("SELECT * FROM users WHERE id = ?", [$_SESSION['user']])->fetch();
            if ($user != null) {
                if ($user['permission'] == "admin") {
                } else {
                    return $this->redirect("home");
                }
            } else {
                return $this->redirect("home");
            }
        } else {
            return $this->redirect("home");
        }
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

    protected function saveImage($image, $path, $imageName = null)
    {
        if ($imageName) {
            $extention = explode("/", $image['type'])[1];
            $imageName = $imageName . "." . $extention;
        } else {
            $extention = explode("/", $image['type'])[1];
            $imageName = date("Y-m-d-H-i-s") . "." . $extention;
        }
        $imageTemp = $image['tmp_name'];
        $imagePath = "public/" . $path . "/";
        if (is_uploaded_file($imageTemp)) {
            if (move_uploaded_file($imageTemp, $imagePath . $imageName)) {
                return $imagePath . $imageName;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    protected function removeImage($imagePath)
    {
        $imagePath = trim($imagePath, "/ ");
        // dd(file_exists($imagePath));
        if (file_exists($imagePath)) {
            unlink($imagePath);
        } else {
            return false;
        }
    }

    public function index(){
        return view("template.admin.index.php");
    }
}
