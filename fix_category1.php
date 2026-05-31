<?php

require_once "config/db.php";

$stmt = $pdo->prepare("
    UPDATE categories
    SET name = 'Письменные принадлежности'
    WHERE id = 1
");

$stmt->execute();

echo "Готово";