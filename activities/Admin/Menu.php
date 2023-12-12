<?php

namespace Admin;

use database\DataBase;

class Menu extends Admin{
    public function index(){
        $db = new DataBase();
        $menus = $db->select("SELECT menus.*, m.name as parent_name FROM menus LEFT JOIN menus m ON menus.parent_id = m.id ORDER BY `id` DESC")->fetchAll();
        // dd($menus);
        view("template.admin.menus.index.php", compact('menus'));
    }

    public function create(){
        $db = new DataBase();
        $menus = $db->select("SELECT * FROM menus")->fetchAll();
        view("template.admin.menus.create.php", compact("menus"));
    }

    public function store($request) {
        $db = new DataBase();
        if($request['parent_id'] == ""){
            unset($request['parent_id']);
        }
        $result = $db->insert("menus", array_keys($request), $request);
        return $this->redirect("admin/menu");
    }

    public function edit($id){
        $db = new DataBase();
        $menu = $db->select("SELECT * FROM menus WHERE id = ?", [$id])->fetch();
        $db = new DataBase();
        $menus = $db->select("SELECT * FROM menus")->fetchAll();
        foreach($menus as $key => $menuP){
            if($menuP['id'] == $menu['id']){
                unset($menus[$key]);
            }
        }
        view("template.admin.menus.edit.php", compact('menu', 'menus'));
    }
    
    public function update($request, $id){
        $db = new DataBase();
        $result = $db->update("menus", $id, array_keys($request), $request);
        return $this->redirect("admin/menu");
    }

    public function delete($id){
        $db = new DataBase();
        $result = $db->delete("menus", $id);
        return $this->redirectBack();
    }
}