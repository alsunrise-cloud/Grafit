<?php
require_once "config/db.php";
require_once "includes/auth.php";

requireRole(['admin']);

$orders = $pdo->query("
    SELECT *
    FROM orders
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ГРАФИТ — Заказы</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body class="admin-body">

<div class="admin-layout">

    <aside class="admin-sidebar">

        <div class="admin-brand">
            <a href="index.php" class="admin-logo-link">
                <span class="admin-crown">♕</span>
                <span class="admin-logo-text">ГРАФИТ</span>
            </a>
            <p>премиальные канцтовары</p>
        </div>

        <nav class="admin-nav">
            <a href="index.php"><span>⌂</span>Главная</a>
            <a href="admin_products.php"><span>▧</span>Товары</a>
            <a href="manager_stock.php"><span>▦</span>Склад</a>
            <a href="admin_orders.php" class="active"><span>▤</span>Заказы</a>
            <a href="admin_users.php"><span>♙</span>Пользователи</a>
        </nav>

        <a href="logout.php" class="admin-logout-btn">
            <span>↪</span>Выход
        </a>

    </aside>

    <main class="admin-content">

        <div class="admin-header-row">
            <div>
                <h1>Управление заказами</h1>
                <p>Просматривайте данные покупателей и состав заказов</p>
            </div>

            <div class="admin-breadcrumbs">
                <a href="index.php">⌂ Главная</a>
                <span>›</span>
                <b>Заказы</b>
            </div>
        </div>

        <section class="admin-products-panel">

            <div class="admin-table-wrap">

                <table class="admin-products-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Покупатель</th>
                            <th>Контакты</th>
                            <th>Адрес</th>
                            <th>Товары</th>
                            <th>Сумма</th>
                            <th>Статус</th>
                            <th>Дата</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="8">
                                <div class="admin-empty">
                                    <div class="empty-icon">▱</div>
                                    <p>Заказов пока нет</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $order['id'] ?></td>

                            <td>
                                <strong><?= htmlspecialchars($order['customer_name'] ?? 'Не указано') ?></strong>
                                <?php if (!empty($order['customer_comment'])): ?>
                                    <br>
                                    <small>Комментарий: <?= htmlspecialchars($order['customer_comment']) ?></small>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($order['customer_phone'] ?? '') ?><br>
                                <?= htmlspecialchars($order['customer_email'] ?? '') ?>
                            </td>

                            <td><?= nl2br(htmlspecialchars($order['customer_address'] ?? '')) ?></td>

                            <td>
                                <pre class="order-items-text"><?= htmlspecialchars($order['items'] ?? '') ?></pre>
                            </td>

                            <td><strong><?= $order['total'] ?> ₽</strong></td>

                            <td>
                                <span class="order-status">
                                    <?= htmlspecialchars($order['status'] ?? 'Новый') ?>
                                </span>
                            </td>

                            <td><?= $order['created_at'] ?></td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>

            </div>

        </section>

    </main>

</div>

</body>
</html>