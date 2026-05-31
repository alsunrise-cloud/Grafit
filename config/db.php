<?php

$host = "dpg-d8dlcq3bc2fs73eodgsg-a.oregon-postgres.render.com";
$port = "5432";
$dbname = "grafit_z5oy";
$user = "grafit_user";
$password = "ТВОЙ_ПАРОЛЬ";

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require",
        $user,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET client_encoding TO 'UTF8'");

} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}