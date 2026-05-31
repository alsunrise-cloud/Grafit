<?php
require_once "config/db.php";
require_once "includes/auth.php";

requireAuth();

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$cartItems = [];
$total = 0;

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

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare("
            INSERT INTO orders (user_id, total, status)
            VALUES (?, ?, ?)
            RETURNING id
        ");

        $stmt->execute([
            $_SESSION['user']['id'],
            $total,
            'new'
        ]);

        $orderId = $stmt->fetchColumn();

        foreach ($cartItems as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'];

            $stmt = $pdo->prepare("
                INSERT INTO order_items 
                (order_id, product_id, quantity, price)
                VALUES (?, ?, ?, ?)
            ");

            $stmt->execute([
                $orderId,
                $product['id'],
                $quantity,
                $product['price']
            ]);

            $stmt = $pdo->prepare("
                UPDATE products
                SET stock = stock - ?
                WHERE id = ?
            ");

            $stmt->execute([
                $quantity,
                $product['id']
            ]);
        }

        $pdo->commit();

        unset($_SESSION['cart']);

        header("Location: profile.php?order=success");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Ошибка при оформлении заказа";
    }
}

include "includes/header.php";
?>

<section class="cart-page">
    <h1>Оформление заказа</h1>

    <?php if (!empty($error)): ?>
        <div class="message"><?= $error ?></div>
    <?php endif; ?>

    <?php foreach ($cartItems as $item): ?>
        <div class="cart-item">
            <img src="assets/images/<?= htmlspecialchars($item['product']['image']) ?>" alt="Товар">

            <div>
                <h3><?= htmlspecialchars($item['product']['title']) ?></h3>
                <p>Количество: <?= $item['quantity'] ?></p>
                <p>Сумма: <?= $item['sum'] ?> ₽</p>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="cart-total">
        <h3>Итого к оплате</h3>
        <p><strong><?= $total ?> ₽</strong></p>
        <br>

        <form method="POST">
            <button type="submit">Подтвердить заказ</button>
        </form>
    </div>
</section>

<?php include "includes/footer.php"; ?>