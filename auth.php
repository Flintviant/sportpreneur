<?php
session_start();

function requireLogin() {
    if (!isset($_SESSION['id_login'])) {
        header("Location: login.php?msg=login_required");
        exit;
    }
}

function requireRole($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        exit('Akses ditolak');
    }
}
