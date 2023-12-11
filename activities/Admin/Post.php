<?php

namespace Admin;

use database\DataBase;

class Post extends Admin
{
    public function index()
    {
        $db = new DataBase();
        // $posts = $db->select("SELECT * FROM posts LEFT JOIN users ON posts.user_id = users.id LEFT JOIN categories ON posts.cat_id = categories.id")->fetchAll();
        $posts = $db->select("SELECT p.*, u.username AS username, c.name AS name FROM posts p LEFT JOIN users u ON p.user_id = u.id LEFT JOIN categories c ON p.cat_id = c.id")->fetchAll();
        // require_once(trim($this->basePath, "/ ") . "/template/admin/categories/index.php");
        view("template.admin.posts.index.php", compact('posts'));
    }

    public function create()
    {
        $db = new DataBase();
        $categories = $db->select("SELECT * FROM categories ORDER BY `id` DESC");
        view("template.admin.posts.create.php", compact('categories'));
    }

    public function store($request)
    {
        date_default_timezone_set('Iran');

        $db = new DataBase();
        $realTimeStamp = substr($request['published_at'], 0, 10);
        $published_at = date("Y-m-d H:i:s", (int)$realTimeStamp);
        $request['published_at'] = $published_at;
        if ($request['cat_id'] != null) {

            $request['user_id'] = 1;
            $imageUploadResult = $this->saveImage($request['image'], 'posts');
            // dd($imageUploadResult);
            $request['image'] = $imageUploadResult;
            $result = $db->insert("posts", array_keys($request), $request);
        }
        return $this->redirect("admin/post");
    }

    /*
    /   :(
        breaking news and selected change status methods
    /   :)
    */

    public function breakingNewsChangeStatus($id)
    {
        $db = new DataBase;
        $posts = $db->select("SELECT * FROM posts WHERE id = ?", [$id])->fetch();
        $status = null;
        if((int)$posts['breaking_news'] === 1){
            $status = 0;
        }else{
            $status = 1;
        }
        $values = array();
        $values['breaking_news'] = $status;
        $db->update("posts", $id, array_keys($values), $values);
        return $this->redirectBack();
    }
    public function selectedChangeStatus($id)
    {
        $db = new DataBase;
        $posts = $db->select("SELECT * FROM posts WHERE id = ?", [$id])->fetch();
        $status = null;
        if((int)$posts['selected'] === 1){
            $status = 0;
        }else{
            $status = 1;
        }
        $values = array();
        $values['selected'] = $status;
        $db->update("posts", $id, array_keys($values), $values);
        return $this->redirectBack();
    }

    public function edit($id)
    {
        $db = new DataBase();
        $post = $db->select("SELECT * FROM posts WHERE id = ?", [$id])->fetch();
        $categories = $db->select("SELECT * FROM categories")->fetchAll();
        // dd($category);
        view("template.admin.posts.edit.php", compact('post', 'categories'));
    }

    public function update($request, $id)
    {
        date_default_timezone_set('Iran');

        $db = new DataBase();
        $realTimestampt = substr($request['published_at'], 0, 10);
        $request['published_at'] = date("Y-m-d H:i:s", (int)$realTimestampt);
        if(!empty($request['image']['tmp_name'])){
            $posts = $db->select("SELECT * FROM posts WHERE id = ?", [$id])->fetch();
            $this->removeImage($posts['image']);
            $imageUploadResult = $this->saveImage($request['image'], 'posts');
            $request['image'] = $imageUploadResult;
        }else{
            unset($request['image']);
        }
       
        $result = $db->update("posts", $id, array_keys($request), $request);
        return $this->redirect("admin/post");
    }

    public function delete($id)
    {
        $db = new DataBase();
        $post = $db->select("SELECT * FROM posts WHERE id = ?", [$id])->fetch();
        $this->removeImage($post['image']);
        $result = $db->delete("posts", $id);
        return $this->redirectBack();
    }
}