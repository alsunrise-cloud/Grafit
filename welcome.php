<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['welcome_user'])) {
    header("Location: index.php");
    exit;
}

$user = $_SESSION['welcome_user'];
unset($_SESSION['welcome_user']);

include "includes/header.php";
?>

<div class="welcome-box">

    <div class="welcome-icon">
        ♕
    </div>

    <h1>Добро пожаловать!</h1>

    <p>
        <?= htmlspecialchars($user['name']) ?>, вы успешно зарегистрировались в магазине ГРАФИТ.
    </p>

    <div class="welcome-number">
        Вы зарегистрированы под номером
        <strong>№<?= (int)$user['number'] ?></strong>
    </div>

    <a href="catalog.php" class="btn">
        Перейти в каталог
    </a>

</div>

<?php include "includes/footer.php"; ?>