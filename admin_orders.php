<?php
require_once "config/db.php";
require_once "includes/auth.php";

requireRole(['admin']);

$orders = $pdo->query("
    SELECT 
        orders.id,
        orders.total,
        orders.status,
        orders.created_at,
        users.name AS user_name,
        users.email AS user_email
    FROM orders
    LEFT JOIN users ON orders.user_id = users.id
    ORDER BY orders.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

$totalOrders = count($orders);
$totalRevenue = $pdo->query("SELECT COALESCE(SUM(total), 0) FROM orders")->fetchColumn();
$newOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'new'")->fetchColumn();
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
            <a href="admin_orders.php" class="active"><span>🛒</span>Заказы</a>
            <a href="admin_users.php"><span>👥</span>Пользователи</a>
        </nav>

        <a href="logout.php" class="admin-logout-btn"><span>↪</span>Выход</a>
    </aside>

    <main class="admin-content">

        <div class="admin-header-row">
            <div>
                <h1>Управление заказами</h1>
                <p>Просматривайте оформленные заказы клиентов</p>
            </div>

            <div class="admin-breadcrumbs">
                <a href="index.php">⌂ Главная</a>
                <span>›</span>
                <b>Заказы</b>
            </div>
        </div>

        <div class="admin-stats-row">
            <div class="admin-stat">
                <div class="stat-icon">🛒</div>
                <div>
                    <p>Всего заказов</p>
                    <strong><?= $totalOrders ?></strong>
                    <small>в системе</small>
                </div>
            </div>

            <div class="admin-stat">
                <div class="stat-icon">₽</div>
                <div>
                    <p>Выручка</p>
                    <strong><?= $totalRevenue ?> ₽</strong>
                    <small>общая сумма</small>
                </div>
            </div>

            <div class="admin-stat">
                <div class="stat-icon">✦</div>
                <div>
                    <p>Новые заказы</p>
                    <strong><?= $newOrders ?></strong>
                    <small>ожидают обработки</small>
                </div>
            </div>
        </div>

        <section class="admin-products-panel">

            <div class="admin-table-wrap">
                <table class="admin-products-table">
                    <thead>
                        <tr>
                            <th>ID заказа</th>
                            <th>Покупатель</th>
                            <th>Email</th>
                            <th>Сумма</th>
                            <th>Статус</th>
                            <th>Дата</th>
                            <th>Просмотр</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="7">
                                    <div class="admin-empty">
                                        <div class="empty-icon">▱</div>
                                        <p>Заказы пока отсутствуют</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= $order['id'] ?></td>
                                <td class="admin-product-name"><?= htmlspecialchars($order['user_name'] ?? 'Пользователь удалён') ?></td>
                                <td><?= htmlspecialchars($order['user_email'] ?? '-') ?></td>
                                <td><?= $order['total'] ?> ₽</td>
                                <td><span class="admin-category-tag"><?= htmlspecialchars($order['status']) ?></span></td>
                                <td><?= $order['created_at'] ?></td>
                                <td>
                                    <a href="order_view.php?id=<?= $order['id'] ?>" class="admin-view-text">
                                        Открыть
                                    </a>
                                </td>
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