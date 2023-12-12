<?php

namespace Admin;

use database\DataBase;

class Setting extends Admin{
    public function index(){
        $db = new DataBase();
        $setting = $db->select("SELECT * FROM setting ORDER BY `id` DESC")->fetch();
        view("template.admin.setting.index.php", compact('setting'));
    }

    public function show(){
        $db = new DataBase();
        $setting = $db->select("SELECT * FROM setting")->fetch();
        view("template.admin.setting.show.php", compact('setting'));
    }

    public function update($request){
        $db = new DataBase();
        $setting = $db->select("SELECT * FROM setting")->fetch();
    
        if($setting == null){
            if($request['icon']['tmp_name'] != null){
                $imageUploadResult = $this->saveImage($request['icon'], 'icon');
                $request['icon'] = $imageUploadResult;
            }else{
                unset($request['icon']);
            }
            if($request['logo']['tmp_name'] != null){
                $imageUploadResult = $this->saveImage($request['logo'], 'logo');
                $request['logo'] = $imageUploadResult;
            }else{
                unset($request['logo']);
            }
            $db->insert("setting", array_keys($request), $request);
        }else{
            if($request['icon']['tmp_name'] != null){
                $this->removeImage($setting['icon']);
                $imageUploadResult = $this->saveImage($request['icon'], 'icon');
                $request['icon'] = $imageUploadResult;
            }else{
                unset($request['icon']);
            }
            if($request['logo']['tmp_name'] != null){
                $this->removeImage($setting['logo']);
                $imageUploadResult = $this->saveImage($request['logo'], 'logo');
                $request['logo'] = $imageUploadResult;
            }else{
                unset($request['logo']);
            }
            $result = $db->update("setting", $setting['id'], array_keys($request), $request);
        }
        return $this->redirect("admin/setting");
    }

}