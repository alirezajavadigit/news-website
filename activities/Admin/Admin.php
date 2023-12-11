<?php

namespace Admin;


class Admin{
    protected $currentDomain;
    protected $basePath;

    function __construct(){
        $this->currentDomain = CURRENT_DOMAIN;
        $this->basePath = BASE_PATH;
    }

    protected function redirect($to){
        $to = trim($this->currentDomain, "/ ") . "/" . trim($to, "/ ");
        header("Location: ". $to);
        exit();
    }

    protected function redirectBack(){
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        exit();
    }

    protected function saveImage($image, $path, $imageName = null){
        if($imageName){
            $extention = explode("/", $image['type'])[1];
            $imageName = $imageName . "." . $extention;
        }else{
            $extention = explode("/", $image['type'])[1];
            $imageName = date("Y-m-d-H-i-s") . "." . $extention;
        }
        $imageTemp = $image['tmp_name'];
        $imagePath = "public/" . $path . "/";
        if(is_uploaded_file($imageTemp)){
            if(move_uploaded_file($imageTemp, $imagePath . $imageName)){
                return $imagePath . $imageName;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
    protected function removeImage($imagePath){
        $imagePath = trim($imagePath, "/ ");
        // dd(file_exists($imagePath));
        if(file_exists($imagePath)){
            unlink($imagePath);
        }else{
            return false;
        }
    }
}