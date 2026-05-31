<?php
require_once "config/db.php";
require_once "includes/auth.php";

if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];

    if (!in_array($id, $_SESSION['favorites'])) {
        $_SESSION['favorites'][] = $id;
    }

    header("Location: favorites.php");
    exit;
}

if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];

    $_SESSION['favorites'] = array_filter(
        $_SESSION['favorites'],
        fn($item) => (int)$item !== $id
    );

    header("Location: favorites.php");
    exit;
}

$favoriteProducts = [];

if (!empty($_SESSION['favorites'])) {
    $ids = array_values($_SESSION['favorites']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare("
        SELECT products.*, categories.name AS category_name
        FROM products
        LEFT JOIN categories ON products.category_id = categories.id
        WHERE products.id IN ($placeholders)
    ");

    $stmt->execute($ids);
    $favoriteProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

include "includes/header.php";
?>

<section class="favorites-page">

    <h1>Избранное</h1>

    <?php if (empty($favoriteProducts)): ?>

        <div class="small-window">
            <h2>Список избранного пуст</h2>
            <p>Добавьте товары, чтобы они появились здесь.</p>
            <a href="catalog.php" class="btn">Перейти в каталог</a>
        </div>

    <?php else: ?>

        <div class="small-window">
            <?php foreach ($favoriteProducts as $product): ?>
                <div class="favorite-item">

                    <?php if (!empty($product['image'])): ?>
                        <img 
                            src="assets/images/<?= htmlspecialchars($product['image']) ?>" 
                            alt="<?= htmlspecialchars($product['title']) ?>"
                        >
                    <?php else: ?>
                        <div class="mini-no-image">Нет фото</div>
                    <?php endif; ?>

                    <div>
                        <h3><?= htmlspecialchars($product['title']) ?></h3>
                        <p><?= htmlspecialchars($product['category_name'] ?? 'Без категории') ?></p>
                        <strong><?= $product['price'] ?> ₽</strong>

                        <div class="favorite-actions">
                            <a href="product.php?id=<?= $product['id'] ?>">Открыть</a>
                            <a href="favorites.php?remove=<?= $product['id'] ?>" class="remove-link">Удалить</a>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</section>

<?php include "includes/footer.php"; ?>