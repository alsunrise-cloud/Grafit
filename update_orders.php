<?php
require_once "config/db.php";

try {
    $pdo->exec("ALTER TABLE orders ADD COLUMN IF NOT EXISTS customer_name VARCHAR(255);");
    $pdo->exec("ALTER TABLE orders ADD COLUMN IF NOT EXISTS customer_phone VARCHAR(50);");
    $pdo->exec("ALTER TABLE orders ADD COLUMN IF NOT EXISTS customer_email VARCHAR(255);");
    $pdo->exec("ALTER TABLE orders ADD COLUMN IF NOT EXISTS customer_address TEXT;");
    $pdo->exec("ALTER TABLE orders ADD COLUMN IF NOT EXISTS customer_comment TEXT;");
    $pdo->exec("ALTER TABLE orders ADD COLUMN IF NOT EXISTS items TEXT;");

    echo "OK — таблица orders обновлена";
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}