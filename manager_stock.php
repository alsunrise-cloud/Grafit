<?php
require_once "config/db.php";
require_once "includes/auth.php";

requireRole(['admin', 'manager']);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productId = (int) $_POST["product_id"];
    $stock = (int) $_POST["stock"];

    if ($stock >= 0) {
        $stmt = $pdo->prepare("UPDATE products SET stock = ? WHERE id = ?");
        $stmt->execute([$stock, $productId]);
        $message = "Количество товара обновлено";
    }
}

$products = $pdo->query("
    SELECT products.*, categories.name AS category_name
    FROM products
    LEFT JOIN categories ON products.category_id = categories.id
    ORDER BY products.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

$totalProducts = count($products);
$totalStock = $pdo->query("SELECT COALESCE(SUM(stock), 0) FROM products")->fetchColumn();
$lowStock = $pdo->query("SELECT COUNT(*) FROM products WHERE stock <= 5")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ГРАФИТ — Склад</title>
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
            <a href="manager_stock.php" class="active"><span>▦</span>Склад</a>
            <a href="admin_orders.php"><span>🛒</span>Заказы</a>
            <a href="admin_users.php"><span>👥</span>Пользователи</a>
        </nav>

        <a href="logout.php" class="admin-logout-btn"><span>↪</span>Выход</a>
    </aside>

    <main class="admin-content">

        <div class="admin-header-row">
            <div>
                <h1>Управление складом</h1>
                <p>Контролируйте количество товаров на складе</p>
            </div>

            <div class="admin-breadcrumbs">
                <a href="index.php">⌂ Главная</a>
                <span>›</span>
                <b>Склад</b>
            </div>
        </div>

        <div class="admin-stats-row">
            <div class="admin-stat">
                <div class="stat-icon">▧</div>
                <div>
                    <p>Всего товаров</p>
                    <strong><?= $totalProducts ?></strong>
                    <small>в каталоге</small>
                </div>
            </div>

            <div class="admin-stat">
                <div class="stat-icon">⌂</div>
                <div>
                    <p>На складе</p>
                    <strong><?= $totalStock ?> шт.</strong>
                    <small>общее количество</small>
                </div>
            </div>

            <div class="admin-stat">
                <div class="stat-icon">!</div>
                <div>
                    <p>Мало на складе</p>
                    <strong><?= $lowStock ?></strong>
                    <small>5 шт. и меньше</small>
                </div>
            </div>
        </div>

        <section class="admin-products-panel">

            <?php if ($message): ?>
                <div class="admin-message"><?= $message ?></div>
            <?php endif; ?>

            <div class="admin-table-wrap">
                <table class="admin-products-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Товар</th>
                            <th>Категория</th>
                            <th>Цена</th>
                            <th>Количество</th>
                            <th>Сохранить</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="6">
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
                                <td class="admin-product-name"><?= htmlspecialchars($product['title']) ?></td>
                                <td><span class="admin-category-tag"><?= htmlspecialchars($product['category_name'] ?? 'Без категории') ?></span></td>
                                <td><?= $product['price'] ?> ₽</td>

                                <td>
                                    <form method="POST" class="admin-inline-form">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <input type="number" name="stock" value="<?= $product['stock'] ?>" min="0">
                                </td>

                                <td>
                                        <button type="submit">Сохранить</button>
                                    </form>
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