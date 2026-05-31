<?php

$host = "dpg-d8d1cq3bc2fs73eodgsg-a.oregon-postgres.render.com";
$port = "5432";
$dbname = "grafit_z5oy";
$user = "grafit_user";
$password = "mf1cDHRrv1XlGQfBobDmW29BbruZatXp";

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require",
        $user,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}