<?php
require_once "config/db.php";
require_once "includes/auth.php";

$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$search = trim($_GET['search'] ?? '');

$categories = $pdo->query("
    SELECT id, name 
    FROM categories 
    ORDER BY id
")->fetchAll(PDO::FETCH_ASSOC);

if ($search !== '') {
    $stmt = $pdo->prepare("
        SELECT products.*, categories.name AS category_name
        FROM products
        LEFT JOIN categories ON products.category_id = categories.id
        WHERE products.title ILIKE ?
           OR products.description ILIKE ?
        ORDER BY products.id DESC
    ");
    $stmt->execute(["%$search%", "%$search%"]);

} elseif ($categoryId > 0) {
    $stmt = $pdo->prepare("
        SELECT products.*, categories.name AS category_name
        FROM products
        LEFT JOIN categories ON products.category_id = categories.id
        WHERE products.category_id = ?
        ORDER BY products.id DESC
    ");
    $stmt->execute([$categoryId]);

} else {
    $stmt = $pdo->query("
        SELECT products.*, categories.name AS category_name
        FROM products
        LEFT JOIN categories ON products.category_id = categories.id
        ORDER BY products.id DESC
    ");
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

include "includes/header.php";
?>

<section class="catalog">

    <?php if ($search !== ''): ?>
        <h1>Поиск: <?= htmlspecialchars($search) ?></h1>
    <?php else: ?>
        <h1>Каталог товаров</h1>
    <?php endif; ?>

    <div class="catalog-layout">

        <aside class="filters">
            <h3>Категории</h3>

            <a href="catalog.php" class="<?= $categoryId === 0 && $search === '' ? 'active-filter' : '' ?>">
                Все товары
            </a>

            <?php foreach ($categories as $category): ?>
                <a 
                    href="catalog.php?category=<?= $category['id'] ?>"
                    class="<?= $categoryId === (int)$category['id'] && $search === '' ? 'active-filter' : '' ?>"
                >
                    <?= htmlspecialchars($category['name']) ?>
                </a>
            <?php endforeach; ?>
        </aside>

        <div class="products-grid">

            <?php if (empty($products)): ?>
                <p class="message">Товары не найдены.</p>
            <?php endif; ?>

            <?php foreach ($products as $product): ?>
                <div class="product-card">

                    <?php if (!empty($product['image'])): ?>
                        <img 
                            src="assets/images/<?= htmlspecialchars($product['image']) ?>" 
                            alt="<?= htmlspecialchars($product['title']) ?>"
                        >
                    <?php else: ?>
                        <div class="product-no-image">Нет фото</div>
                    <?php endif; ?>

                    <h3><?= htmlspecialchars($product['title']) ?></h3>

                    
                    <p class="product-category">
                        Категория: <?= htmlspecialchars($product['category_name'] ?? 'Без категории') ?>
                    </p>

                    <p class="product-stock">
                        В наличии: <?= (int)$product['stock'] ?> шт.
                    </p>

                    <div class="product-bottom">
                        <strong><?= $product['price'] ?> ₽</strong>

                        <a href="product.php?id=<?= $product['id'] ?>" class="btn-small">
                            Подробнее
                        </a>
                    </div>

                </div>
            <?php endforeach; ?>

        </div>

    </div>
</section>

<?php include "includes/footer.php"; ?>