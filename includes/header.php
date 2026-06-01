<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Графит — интернет-магазин канцелярских товаров</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    

<header class="header">

    <a href="/" class="logo">♕ ГРАФИТ</a>

    <nav>
        <a href="index.php">Главная</a>
        <a href="catalog.php">Каталог</a>

        <div class="search-wrapper">
            <button type="button" class="search-toggle" onclick="toggleSearch()">
                ⌕
            </button>

            <form action="catalog.php" method="GET" id="searchForm" class="search-form">
                <input
                    type="text"
                    name="search"
                    placeholder="Поиск товара..."
                >
            </form>
        </div>

        <a href="cart.php" class="header-icon-link">
            <span class="header-icon-circle">🛒</span>
            <span class="header-icon-text">Корзина</span>
        </a>

        <a href="favorites.php" class="header-icon-link">
            <span class="header-icon-circle">♡</span>
            <span class="header-icon-text">Избранное</span>
        </a>

        <?php if (isset($_SESSION['user'])): ?>

            <a href="profile.php">Профиль</a>

            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                <a href="admin.php">Админ-панель</a>
            <?php endif; ?>

            <?php if ($_SESSION['user']['role'] === 'manager'): ?>
                <a href="manager_stock.php">Склад</a>
            <?php endif; ?>

            <a href="logout.php">Выход</a>

        <?php else: ?>

            <a href="login.php">Вход</a>
            <a href="register.php">Регистрация</a>

        <?php endif; ?>

    </nav>
</header>

<main>

<script>
function toggleSearch() {
    const form = document.getElementById('searchForm');
    form.classList.toggle('active');

    const input = form.querySelector('input');

    if (form.classList.contains('active')) {
        setTimeout(() => {
            input.focus();
        }, 200);
    }
}
</script>