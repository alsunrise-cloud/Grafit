<?php
require_once "config/db.php";
require_once "includes/auth.php";

if (!isset($_GET['id'])) {
    header("Location: catalog.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("
    SELECT products.*, categories.name AS category_name
    FROM products
    LEFT JOIN categories ON products.category_id = categories.id
    WHERE products.id = ?
");

$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: catalog.php");
    exit;
}

$image = !empty($product['image'])
    ? "assets/images/" . htmlspecialchars($product['image'])
    : "assets/images/no-image.png";

include "includes/header.php";
?>

<section class="product-page premium-product-page">

    <a href="catalog.php" class="back-link">← Все товары</a>

    <div class="premium-product-card">

        <div class="premium-product-gallery">
            <div class="premium-main-image-wrap">
                <img 
                    src="<?= $image ?>" 
                    alt="<?= htmlspecialchars($product['title']) ?>"
                    class="premium-main-image"
                >
            </div>

            <div class="premium-thumbs">
                <div class="premium-thumb active">
                    <img 
                        src="<?= $image ?>" 
                        alt="<?= htmlspecialchars($product['title']) ?>"
                    >
                </div>
            </div>
        </div>

        <div class="premium-product-info">

            <span class="premium-label">
                <?= htmlspecialchars($product['category_name'] ?? 'Без категории') ?>
            </span>

            <h1><?= htmlspecialchars($product['title']) ?></h1>

            <div class="premium-price">
                <?= number_format((float)$product['price'], 2, '.', ' ') ?> ₽
            </div>

            <div class="premium-stock">
                В наличии: <?= (int)$product['stock'] ?> шт.
            </div>

            <div class="premium-quantity">
                <span>Количество</span>

                <div class="quantity-box">
                    <button type="button">−</button>
                    <input type="number" value="1" min="1">
                    <button type="button">+</button>
                </div>
            </div>

            <?php if ((int)$product['stock'] > 0): ?>
                <div class="premium-buttons">
                    <a href="cart.php?add=<?= $product['id'] ?>" class="premium-cart-btn">
                        🛒 Добавить в корзину
                    </a>

                    <a href="favorites.php?add=<?= $product['id'] ?>" class="premium-fav-btn">
                        ♡ В избранное
                    </a>
                </div>
            <?php else: ?>
                <p class="message">Товара нет в наличии</p>
            <?php endif; ?>

            <div class="premium-features">
                <div>
                    <span>◇</span>
                    <p>Премиальное качество</p>
                </div>

                <div>
                    <span>▣</span>
                    <p>Надёжная упаковка</p>
                </div>

                <div>
                    <span>✦</span>
                    <p>Быстрая доставка</p>
                </div>
            </div>

        </div>

    </div>

    <div class="premium-description-card">
        <div class="description-title">
            <h2>Описание</h2>
        </div>

        <div class="description-text">
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        </div>
    </div>

</section>

<?php include "includes/footer.php"; ?>