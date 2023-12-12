<?php

namespace Admin;

use database\DataBase;

class Setting extends Admin{
    public function index(){
        $db = new DataBase();
        $setting = $db->select("SELECT * FROM setting ORDER BY `id` DESC")->fetch();
        view("template.admin.setting.index.php", compact('setting'));
    }

    public function show($id){
        $db = new DataBase();
        $setting = $db->select("SELECT * FROM setting WHERE id = ?", [$id])->fetch();
        view("template.admin.setting.show.php", compact('setting'));
    }

    public function update($request, $id){
        $db = new DataBase();
        $result = $db->update("setting", $id, array_keys($request), $request);
        return $this->redirect("admin/setting");
    }

}