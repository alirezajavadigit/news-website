<?php

namespace Admin;

use database\DataBase;

class Comment extends Admin{
    public function index(){
        $db = new DataBase();
        $comments = $db->select("SELECT * FROM comments ORDER BY `id` DESC")->fetchAll();
        view("template.admin.comments.index.php", compact('comments'));
    }

    public function show($id){
        $db = new DataBase();
        $values = array();
        $values['status'] = "seen";
        $db->update("comments", $id, array_keys($values), $values);
        view("template.admin.comments.show.php");
    }

    public function approveToggle($id){
        $db = new DataBase();
        $comment = $db->select("SELECT * FROM comments WHERE id = ?", [$id])->fetch();
        if($comment['status'] == "unseen" || $comment['status'] == "seen"){
            $values = array();
            $values['status'] = "approved";
            $db->update("comments", $id, array_keys($values), $values);
        }else{
            $values = array();
            $values['status'] = "seen";
            $db->update("comments", $id, array_keys($values), $values);
        }
        return $this->redirectBack();
    }
}