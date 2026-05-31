<?php

require_once "config/db.php";
require_once "includes/auth.php";

requireRole(['admin']);

if (!isset($_GET['id'])) {
    header("Location: admin_products.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("
    DELETE FROM products
    WHERE id = ?
");

$stmt->execute([$id]);

header("Location: admin_products.php");

exit;