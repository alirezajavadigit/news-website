<?php

namespace Admin;

use database\DataBase;

class Post extends Admin{
    public function index(){
        $db = new DataBase();
        $posts = $db->select("SELECT * FROM posts ORDER BY `id` DESC");
        // require_once(trim($this->basePath, "/ ") . "/template/admin/categories/index.php");
        view("template.admin.posts.index.php", compact('posts'));
    }
    
    public function create(){
        $db = new DataBase();
        $categories = $db->select("SELECT * FROM categories ORDER BY `id` DESC");
        view("template.admin.posts.create.php", compact('categories'));
    }

    public function store($request) {
        $db = new DataBase();
        // dd($request['image']);
        $request['user_id'] = 1;
        $imageUploadResult = $this->saveImage($request['image'], 'posts');
        // dd($imageUploadResult);
        $request['image'] = $imageUploadResult;
        $result = $db->insert("posts", array_keys($request), $request);
        return $this->redirect("admin/post");
    }

    public function edit($id){
        $db = new DataBase();
        $post = $db->select("SELECT * FROM posts WHERE id = ?", [$id]);
        // dd($category);
        view("template.admin.posts.edit.php", compact('post'));
    }
    
    public function update($request, $id){
        $db = new DataBase();
        $result = $db->update("posts", $id, array_keys($request), $request);
        return $this->redirect("admin/post");
    }

    public function delete($id){
        $db = new DataBase();
        $result = $db->delete("posts", $id);
        return $this->redirectBack();
    }
}