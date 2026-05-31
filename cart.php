<?php
require_once "config/db.php";
require_once "includes/auth.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['add'])) {
    $productId = (int) $_GET['add'];

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]++;
    } else {
        $_SESSION['cart'][$productId] = 1;
    }

    header("Location: cart.php");
    exit;
}

if (isset($_GET['remove'])) {
    $productId = (int) $_GET['remove'];
    unset($_SESSION['cart'][$productId]);

    header("Location: cart.php");
    exit;
}

$cartItems = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $product) {
        $quantity = $_SESSION['cart'][$product['id']];
        $sum = $product['price'] * $quantity;

        $cartItems[] = [
            'product' => $product,
            'quantity' => $quantity,
            'sum' => $sum
        ];

        $total += $sum;
    }
}

include "includes/header.php";
?>

<section class="cart-page">
    <h1>Корзина</h1>

    <?php if (empty($cartItems)): ?>

        <p>Корзина пуста.</p>
        <br>
        <a href="catalog.php" class="btn">Перейти в каталог</a>

    <?php else: ?>

        <?php foreach ($cartItems as $item): ?>
            <div class="cart-item">
                <img src="assets/images/<?= htmlspecialchars($item['product']['image']) ?>" alt="Товар">

                <div>
                    <h3><?= htmlspecialchars($item['product']['title']) ?></h3>
                    <p>Цена: <?= $item['product']['price'] ?> ₽</p>
                    <p>Количество: <?= $item['quantity'] ?></p>
                    <p>Сумма: <?= $item['sum'] ?> ₽</p>
                </div>

                <a 
                    href="cart.php?remove=<?= $item['product']['id'] ?>" 
                    class="delete-link"
                >
                    Удалить
                </a>
            </div>
        <?php endforeach; ?>

        <div class="cart-total">
            <h3>Итого</h3>
            <p>Общая сумма: <strong><?= $total ?> ₽</strong></p>
            <br>
            <a href="checkout.php" class="btn">Оформить заказ</a>
        </div>

    <?php endif; ?>
</section>

<?php include "includes/footer.php"; ?>