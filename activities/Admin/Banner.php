<?php

namespace Admin;

use database\DataBase;

class Banner extends Admin{
    public function index(){
        $db = new DataBase();
        $banners = $db->select("SELECT * FROM banners ORDER BY `id` DESC")->fetchAll();
        // require_once(trim($this->basePath, "/ ") . "/template/admin/banners/index.php");
        view("template.admin.banners.index.php", compact('banners'));
    }

    public function create(){
        view("template.admin.banners.create.php");
    }

    public function store($request) {
        $db = new DataBase();
        $imageUploadResult = $this->saveImage($request['image'], 'banners');
        $request['image'] = $imageUploadResult;
        $result = $db->insert("banners", array_keys($request), $request);
        return $this->redirect("admin/banner");
    }

    public function edit($id){
        
        $db = new DataBase();
        $banner = $db->select("SELECT * FROM banners WHERE id = ?", [$id])->fetch();
        view("template.admin.banners.edit.php", compact('banner'));
    }
    
    public function update($request, $id){
        $db = new DataBase();
        if(!empty($request['image']['tmp_name'])){
            $banner = $db->select("SELECT * FROM banners WHERE id = ?", [$id])->fetch();
            $this->removeImage($banner['image']);
            $imageUploadResult = $this->saveImage($request['image'], 'banners');
            $request['image'] = $imageUploadResult;
        }else{
            unset($request['image']);
        }
       
        $result = $db->update("banners", $id, array_keys($request), $request);
        return $this->redirect("admin/banner");
    }

    public function delete($id){
        $db = new DataBase();
        $banner = $db->select("SELECT * FROM banners WHERE id = ?", [$id])->fetch();
        $this->removeImage($banner['image']);
        $result = $db->delete("banners", $id);
        return $this->redirectBack();
    }
}