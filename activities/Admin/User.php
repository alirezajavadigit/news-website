<?php

namespace Admin;

use database\DataBase;

class User extends Admin
{
    public function index()
    {
        $db = new DataBase();
        $users = $db->select("SELECT * FROM users ORDER BY `id` DESC")->fetchAll();
        view("template.admin.users.index.php", compact('users'));
    }

    /*
    /   (:
    /   changePermission method is changing users Permission automatically
    /   :)
    */

    public function changePermission($id)
    {
        $db = new DataBase;
        $users = $db->select("SELECT * FROM users WHERE id = ?", [$id])->fetch();
        if ($users['permission'] == "admin") {
            $values = array();
            $values['permission'] = "user";
            $db->update("users", $id, array_keys($values), $values);
        } else {
            $values = array();
            $values['permission'] = "admin";
            $db->update("users", $id, array_keys($values), $values);
        }
        return $this->redirectBack();
    }

    public function edit($id)
    {

        $db = new DataBase();
        $user = $db->select("SELECT * FROM users WHERE id = ?", [$id])->fetch();
        view("template.admin.users.edit.php", compact('user'));
    }

    public function update($request, $id)
    {
        $db = new DataBase();
        $result = $db->update("users", $id, array_keys($request), $request);
        return $this->redirect("admin/user");
    }

    public function delete($id)
    {
        $db = new DataBase();
        $result = $db->delete("users", $id);
        return $this->redirectBack();
    }
}