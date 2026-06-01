<?php
require_once "config/db.php";
require_once "includes/auth.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cartItems = [];
$total = 0;
$message = "";

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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $comment = trim($_POST['comment'] ?? '');

    if ($name === "" || $phone === "" || $email === "" || $address === "") {
        $message = "Заполните обязательные поля";
    } elseif (empty($cartItems)) {
        $message = "Корзина пуста";
    } else {
        $itemsText = "";

        foreach ($cartItems as $item) {
            $itemsText .= $item['product']['title'] . " — " .
                $item['quantity'] . " шт. — " .
                $item['sum'] . " ₽\n";
        }

        $userId = $_SESSION['user']['id'] ?? null;

        $stmt = $pdo->prepare("
            INSERT INTO orders 
            (user_id, customer_name, customer_phone, customer_email, customer_address, customer_comment, items, total, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $userId,
            $name,
            $phone,
            $email,
            $address,
            $comment,
            $itemsText,
            $total,
            "Новый"
        ]);

        $_SESSION['cart'] = [];

        header("Location: checkout_success.php");
        exit;
    }
}

include "includes/header.php";
?>

<section class="checkout-page">

    <h1>Оформление заказа</h1>

    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (empty($cartItems)): ?>

        <div class="small-window">
            <h2>Корзина пуста</h2>
            <p>Добавьте товары перед оформлением заказа.</p>
            <a href="catalog.php" class="btn">Перейти в каталог</a>
        </div>

    <?php else: ?>

        <div class="checkout-layout">

            <form method="POST" class="checkout-form">

                <h2>Данные покупателя</h2>

                <div class="form-group">
                    <input type="text" name="name" placeholder="Ваше имя *" required>
                </div>

                <div class="form-group">
                    <input type="tel" name="phone" placeholder="Телефон *" required>
                </div>

                <div class="form-group">
                    <input type="email" name="email" placeholder="Email *" required>
                </div>

                <div class="form-group">
                    <textarea name="address" placeholder="Адрес доставки *" required></textarea>
                </div>

                <div class="form-group">
                    <textarea name="comment" placeholder="Комментарий к заказу"></textarea>
                </div>

                <button type="submit" class="btn">Подтвердить заказ</button>

            </form>

            <div class="checkout-summary">

                <h2>Ваш заказ</h2>

                <?php foreach ($cartItems as $item): ?>
                    <div class="checkout-item">
                        <span><?= htmlspecialchars($item['product']['title']) ?></span>
                        <small><?= $item['quantity'] ?> шт.</small>
                        <strong><?= $item['sum'] ?> ₽</strong>
                    </div>
                <?php endforeach; ?>

                <div class="checkout-total">
                    Итого:
                    <strong><?= $total ?> ₽</strong>
                </div>

            </div>

        </div>

    <?php endif; ?>

</section>

<?php include "includes/footer.php"; ?>