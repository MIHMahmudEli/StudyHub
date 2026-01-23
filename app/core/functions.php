<?php

function url($path = '') {
    $path = trim($path, '/');
    return BASE_URL . '/' . $path;
}

function redirect($path) {
    header("Location: " . url($path));
    exit();
}

function asset($path) {
    return BASE_URL . '/assets/' . trim($path, '/');
}
