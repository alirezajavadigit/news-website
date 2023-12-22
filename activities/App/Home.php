<?php

namespace App;

use Auth\Auth;
use database\DataBase;

class Home
{
    protected $currentDomain;
    protected $basePath;

    function __construct()
    {
        $this->currentDomain = CURRENT_DOMAIN;
        $this->basePath = BASE_PATH;
    }

    protected function redirect($to)
    {
        $to = trim($this->currentDomain, "/ ") . "/" . trim($to, "/ ");
        header("Location: " . $to);
        exit();
    }

    protected function redirectBack()
    {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        exit();
    }

    public function index()
    {
        $db = new DataBase();
        $selectedPosts = $db->select("SELECT posts.*, (SELECT name FROM categories WHERE posts.cat_id = categories.id) as category_name, (SELECT username FROM users WHERE posts.user_id = users.id) as user_name, (SELECT COUNT(*) FROM comments WHERE posts.id = comments.post_id) as comment_count FROM posts WHERE posts.selected = 1 ORDER BY posts.created_at DESC LIMIT 0,3")->fetchAll();
        $setting = $db->select("SELECT * FROM setting")->fetch();
        $menus = $db->select("SELECT * FROM menus WHERE parent_id IS NULL")->fetchAll();
        $breakingNews = $db->select("SELECT * FROM posts WHERE breaking_news = 1 ORDER BY created_at DESC")->fetch();
        $lastsixPosts = $db->select("SELECT posts.*, (SELECT name FROM categories WHERE posts.cat_id = categories.id) as category_name, (SELECT username FROM users WHERE posts.user_id = users.id) as user_name, (SELECT COUNT(*) FROM comments WHERE posts.id = comments.post_id) as comment_count FROM posts WHERE posts.selected = 1 ORDER BY posts.created_at DESC LIMIT 0,6")->fetchAll();
        $mostViewedPosts = $db->select("SELECT posts.*, (SELECT name FROM categories WHERE posts.cat_id = categories.id) as category_name, (SELECT username FROM users WHERE posts.user_id = users.id) as user_name, (SELECT COUNT(*) FROM comments WHERE posts.id = comments.post_id) as comment_count FROM posts ORDER BY posts.view DESC LIMIT 0,3")->fetchAll();
        $mostCommentedPosts = $db->select("SELECT posts.*, (SELECT name FROM categories WHERE posts.cat_id = categories.id) as category_name, (SELECT username FROM users WHERE posts.user_id = users.id) as user_name, (SELECT COUNT(*) FROM comments WHERE posts.id = comments.post_id) as comment_count FROM posts ORDER BY comment_count DESC LIMIT 0,6")->fetchAll();

        return view("template.app.index.php", compact("setting", "selectedPosts", "menus", "breakingNews", "lastsixPosts", "mostViewedPosts", "mostCommentedPosts"));
    }

    public function postShow($id)
    {
        $db = new DataBase();
        $post = $db->select("SELECT* FROM posts WHERE id = ?", [$id])->fetch();
        return view("template.app.show.php", compact("post"));
    }
    public function categoryShow($id)
    {
        $db = new DataBase();
        $category = $db->select("SELECT* FROM categories WHERE id = ?", [$id])->fetch();
        return view("template.app.category.php", compact("category"));
    }
    public function commentStore($request)
    {
        $db = new DataBase();
        $result = $db->insert("comments", array_keys($request), $request);
        return $this->redirectBack();
    }
}
