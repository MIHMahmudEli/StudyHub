<?php

class Controller {
    public function view($view, $data = []) {
        extract($data);
        if (file_exists("../app/views/" . $view . ".php")) {
            require_once "../app/views/" . $view . ".php";
        } else {
            die("View does not exist: " . $view);
        }
    }
    
    public function redirect($url) {
        // Check if url is already absolute (contains http/https)
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = url($url);
        }
        header("Location: " . $url);
        exit();
    }
    public function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}
