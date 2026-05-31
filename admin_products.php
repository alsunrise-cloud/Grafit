<?php
require_once "config/db.php";
require_once "includes/auth.php";

requireRole(['admin']);

$stmt = $pdo->query("
    SELECT 
        products.*, 
        categories.name AS category_name
    FROM products
    LEFT JOIN categories ON products.category_id = categories.id
    ORDER BY products.id DESC
");

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalProducts = count($products);

$categoriesCount = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$totalStock = $pdo->query("SELECT COALESCE(SUM(stock), 0) FROM products")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ГРАФИТ — Управление товарами</title>
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
            <a href="index.php">
                <span>⌂</span>Главная
            </a>

            <a href="admin_products.php" class="active">
                <span>▧</span>Товары
            </a>

            <a href="manager_stock.php">
                <span>▦</span>Склад
            </a>

            <a href="admin_orders.php">
                <span>▤</span>Заказы
            </a>

            <a href="admin_users.php">
                <span>♙</span>Пользователи
            </a>
        </nav>

        <a href="logout.php" class="admin-logout-btn">
            <span>↪</span>Выход
        </a>

    </aside>

    <main class="admin-content">

        <div class="admin-header-row">
            <div>
                <h1>Управление товарами</h1>
                <p>Добавляйте, редактируйте и управляйте товарами магазина</p>
            </div>

            <div class="admin-breadcrumbs">
                <a href="index.php">⌂ Главная</a>
                <span>›</span>
                <b>Товары</b>
            </div>
        </div>

        <div class="admin-stats-row">

            <div class="admin-stat">
                <div class="stat-icon">▢</div>
                <div>
                    <p>Всего товаров</p>
                    <strong><?= $totalProducts ?></strong>
                    <small>в каталоге</small>
                </div>
            </div>

            <div class="admin-stat">
                <div class="stat-icon">▧</div>
                <div>
                    <p>Категорий</p>
                    <strong><?= $categoriesCount ?></strong>
                    <small>активных</small>
                </div>
            </div>

            <div class="admin-stat">
                <div class="stat-icon">⌂</div>
                <div>
                    <p>На складе</p>
                    <strong><?= $totalStock ?> шт.</strong>
                    <small>товаров</small>
                </div>
            </div>

        </div>

        <section class="admin-products-panel">

            <div class="admin-panel-actions">
                <a href="add_product.php" class="admin-add-product">
                    <span>＋</span>
                    Добавить товар
                </a>

                <div class="admin-product-search">
                    <span>⌕</span>
                    <input type="text" placeholder="Поиск товара...">
                </div>
            </div>

            <div class="admin-table-wrap">

                <table class="admin-products-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Фото</th>
                            <th>Название</th>
                            <th>Категория</th>
                            <th>Цена</th>
                            <th>Склад</th>
                            <th>Действие</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="7">
                                    <div class="admin-empty">
                                        <div class="empty-icon">▱</div>
                                        <p>Товары пока не добавлены</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= $product['id'] ?></td>

                                <td>
                                    <?php if (!empty($product['image'])): ?>
                                        <img 
                                            src="assets/images/<?= htmlspecialchars($product['image']) ?>" 
                                            alt="<?= htmlspecialchars($product['title']) ?>"
                                            class="admin-table-img"
                                        >
                                    <?php else: ?>
                                        <div class="admin-no-img">Нет фото</div>
                                    <?php endif; ?>
                                </td>

                                <td class="admin-product-name">
                                    <?= htmlspecialchars($product['title']) ?>
                                </td>

                                <td>
                                    <span class="admin-category-tag">
                                        <?= htmlspecialchars($product['category_name'] ?? 'Без категории') ?>
                                    </span>
                                </td>

                                <td><?= $product['price'] ?> ₽</td>

                                <td class="admin-stock">
                                    <?= $product['stock'] ?> шт.
                                </td>

                                <td>
                                    <div class="admin-action-buttons">
                                        <a 
                                            href="product.php?id=<?= $product['id'] ?>" 
                                            class="admin-view"
                                            title="Посмотреть товар"
                                        >
                                            ◉
                                        </a>

                                        <a 
                                            href="delete_product.php?id=<?= $product['id'] ?>" 
                                            class="admin-delete"
                                            title="Удалить товар"
                                            onclick="return confirm('Удалить товар?')"
                                        >
                                            ×
                                        </a>
                                    </div>
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