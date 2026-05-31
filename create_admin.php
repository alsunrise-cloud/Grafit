<?php
require_once "config/db.php";

$name = "Администратор";
$email = "admin@grafit.ru";
$password = "admin123";
$role = "admin";

$hash = password_hash($password, PASSWORD_DEFAULT);

$check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$check->execute([$email]);

if ($check->fetch()) {
    $stmt = $pdo->prepare("
        UPDATE users
        SET password = ?, role = ?
        WHERE email = ?
    ");

    $stmt->execute([$hash, $role, $email]);

    echo "Администратор обновлён";
} else {
    $stmt = $pdo->prepare("
        INSERT INTO users (name, email, password, role)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([$name, $email, $hash, $role]);

    echo "Администратор создан";
}

echo "<br>Email: admin@grafit.ru";
echo "<br>Пароль: admin123";
echo "<br><a href='login.php'>Перейти ко входу</a>";