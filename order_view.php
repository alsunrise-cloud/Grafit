<?php
require_once "config/db.php";
require_once "includes/auth.php";

requireRole(['admin']);

if (!isset($_GET['id'])) {
    header("Location: admin_orders.php");
    exit;
}

$orderId = (int) $_GET['id'];

$stmt = $pdo->prepare("
    SELECT 
        orders.id,
        orders.total,
        orders.status,
        orders.created_at,
        users.name AS user_name,
        users.email AS user_email
    FROM orders
    LEFT JOIN users ON orders.user_id = users.id
    WHERE orders.id = ?
");

$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header("Location: admin_orders.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT 
        order_items.quantity,
        order_items.price,
        products.title,
        products.image
    FROM order_items
    LEFT JOIN products ON order_items.product_id = products.id
    WHERE order_items.order_id = ?
");

$stmt->execute([$orderId]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

include "includes/header.php";
?>

<section class="admin-container">

    <h1>Заказ №<?= $order['id'] ?></h1>

    <div class="profile-card">
        <p><strong>Покупатель:</strong> <?= htmlspecialchars($order['user_name'] ?? 'Пользователь удалён') ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($order['user_email'] ?? '-') ?></p>
        <p><strong>Статус:</strong> <?= htmlspecialchars($order['status']) ?></p>
        <p><strong>Дата:</strong> <?= $order['created_at'] ?></p>
        <p><strong>Сумма:</strong> <?= $order['total'] ?> ₽</p>
    </div>

    <br>

    <h2>Состав заказа</h2>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Фото</th>
                    <th>Товар</th>
                    <th>Количество</th>
                    <th>Цена</th>
                    <th>Сумма</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <img 
                                src="assets/images/<?= htmlspecialchars($item['image']) ?>" 
                                alt="<?= htmlspecialchars($item['title']) ?>"
                            >
                        </td>

                        <td><?= htmlspecialchars($item['title']) ?></td>

                        <td><?= $item['quantity'] ?></td>

                        <td><?= $item['price'] ?> ₽</td>

                        <td><?= $item['price'] * $item['quantity'] ?> ₽</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <br>

    <a href="admin_orders.php" class="btn">Назад к заказам</a>

</section>

<?php include "includes/footer.php"; ?>