<?php

namespace Admin;

use database\DataBase;

class Category extends Admin{
    public function index(){
        $db = new DataBase();
        $categories = $db->select("SELECT * FROM categories ORDER BY `id` DESC")->fetchAll();
        // require_once(trim($this->basePath, "/ ") . "/template/admin/categories/index.php");
        view("template.admin.categories.index.php", compact('categories'));
    }

    public function create(){
        view("template.admin.categories.create.php");
    }

    public function store($request) {
        $db = new DataBase();
        // dd(array_keys($request));
        $result = $db->insert("categories", array_keys($request), $request);
        return $this->redirect("admin/category");
    }

    public function edit($id){
        $db = new DataBase();
        $category = $db->select("SELECT * FROM categories WHERE id = ?", [$id])->fetch();
        // dd($category);
        view("template.admin.categories.edit.php", compact('category'));
    }
    
    public function update($request, $id){
        $db = new DataBase();
        $result = $db->update("categories", $id, array_keys($request), $request);
        return $this->redirect("admin/category");
    }

    public function delete($id){
        $db = new DataBase();
        $result = $db->delete("categories", $id);
        return $this->redirectBack();
    }
}