<?php

namespace Admin;

use Auth\Auth;
use database\DataBase;

class Admin
{
    protected $currentDomain;
    protected $basePath;

    function __construct()
    {
        $this->currentDomain = CURRENT_DOMAIN;
        $this->basePath = BASE_PATH;
        $auth = new Auth;
        $db = new DataBase();
        if ($auth->isLogin()) {

            $user = $db->select("SELECT * FROM users WHERE id = ?", [$_SESSION['user']])->fetch();
            if ($user != null) {
                if ($user['permission'] == "admin") {
                } else {
                    return $this->redirect("home");
                }
            } else {
                return $this->redirect("home");
            }
        } else {
            return $this->redirect("home");
        }
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

    protected function saveImage($image, $path, $imageName = null)
    {
        if ($imageName) {
            $extention = explode("/", $image['type'])[1];
            $imageName = $imageName . "." . $extention;
        } else {
            $extention = explode("/", $image['type'])[1];
            $imageName = date("Y-m-d-H-i-s") . "." . $extention;
        }
        $imageTemp = $image['tmp_name'];
        $imagePath = "public/" . $path . "/";
        if (is_uploaded_file($imageTemp)) {
            if (move_uploaded_file($imageTemp, $imagePath . $imageName)) {
                return $imagePath . $imageName;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    protected function removeImage($imagePath)
    {
        $imagePath = trim($imagePath, "/ ");
        // dd(file_exists($imagePath));
        if (file_exists($imagePath)) {
            unlink($imagePath);
        } else {
            return false;
        }
    }

    public function index(){
        $db = new DataBase();
        $categoryCount = $db->select("SELECT COUNT(*) FROM categories")->fetch();
        $userCount = $db->select("SELECT COUNT(*) FROM users")->fetch();
        $adminCount = $db->select("SELECT COUNT(*) FROM users WHERE permission = 'admin';")->fetch();
        $postCount = $db->select("SELECT COUNT(*) FROM posts")->fetch();
        $postsViews = $db->select("SELECT SUM(view) FROM posts")->fetch();
        $commentCount = $db->select("SELECT COUNT(*) FROM comments")->fetch();
        $commentUnseenCount = $db->select("SELECT COUNT(*) FROM comments WHERE status = 'unseen'")->fetch();
        $commentApprovedCount = $db->select("SELECT COUNT(*) FROM comments WHERE status = 'approved'")->fetch();
        $mostViewedPosts = $db->select("SELECT * FROM posts ORDER BY view DESC LIMIT 6")->fetchAll();
        $mostCommentedPosts = $db->select("SELECT posts.title, COUNT(comments.post_id) as comment_count  FROM posts LEFT JOIN comments ON posts.id = comments.post_id GROUP BY posts.id  ORDER BY comment_count DESC LIMIT 6")->fetchAll();
        $comments = $db->select("SELECT comments.id, comments.comment, comments.status, users.username as user_name FROM comments LEFT JOIN users ON comments.user_id = users.id ORDER BY comments.id DESC LIMIT 6")->fetchAll();
        return view("template.admin.index.php", compact("commentApprovedCount","commentUnseenCount","categoryCount", "userCount", "adminCount", "postCount", "postsViews", "commentCount", "mostViewedPosts", "mostCommentedPosts", "comments"));
    }
}
