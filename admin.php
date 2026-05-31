<?php
require_once "includes/auth.php";

requireRole(['admin']);

include "includes/header.php";
?>

<section class="admin-container">
    <h1>Административная панель</h1>

    <div class="admin-grid">

        <div class="admin-card">
            <h3>Товары</h3>
            <p>Добавление, просмотр и удаление товаров каталога.</p>
            <a href="admin_products.php" class="btn-small">Открыть</a>
        </div>

        <div class="admin-card">
            <h3>Пользователи</h3>
            <p>Управление пользователями и их ролями.</p>
            <a href="admin_users.php" class="btn-small">Открыть</a>
        </div>

        <div class="admin-card">
            <h3>Заказы</h3>
            <p>Просмотр оформленных заказов клиентов.</p>
            <a href="admin_orders.php" class="btn-small">Открыть</a>
        </div>

        <div class="admin-card">
            <h3>Склад</h3>
            <p>Контроль количества товаров на складе.</p>
            <a href="manager_stock.php" class="btn-small">Открыть</a>
        </div>

    </div>
</section>

<?php include "includes/footer.php"; ?>