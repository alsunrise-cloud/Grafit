<?php
session_start();

function isAuth() {
    return isset($_SESSION['user']);
}

function currentUser() {
    return $_SESSION['user'] ?? null;
}

function requireAuth() {
    if (!isAuth()) {
        header("Location: login.php");
        exit;
    }
}

function requireRole($roles) {
    requireAuth();

    if (!in_array($_SESSION['user']['role'], $roles)) {
        die("Нет доступа");
    }
}