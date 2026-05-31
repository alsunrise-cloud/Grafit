<?php
require_once "config/db.php";
require_once "includes/auth.php";

requireRole(['admin']);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = (int) $_POST["user_id"];
    $role = $_POST["role"];

    if (in_array($role, ['user', 'manager', 'admin'])) {
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$role, $userId]);
        $message = "Роль пользователя обновлена";
    }
}

if (isset($_GET['delete'])) {
    $userId = (int) $_GET['delete'];

    if ($userId !== $_SESSION['user']['id']) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
    }

    header("Location: admin_users.php");
    exit;
}

$users = $pdo->query("
    SELECT id, name, email, role
    FROM users
    ORDER BY id ASC
")->fetchAll(PDO::FETCH_ASSOC);

$totalUsers = count($users);
$adminsCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
$managersCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'manager'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ГРАФИТ — Пользователи</title>
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
            <a href="admin_orders.php"><span>🛒</span>Заказы</a>
            <a href="admin_users.php" class="active"><span>👥</span>Пользователи</a>
        </nav>

        <a href="logout.php" class="admin-logout-btn">
            <span>↪</span>Выход
        </a>

    </aside>

    <main class="admin-content">

        <div class="admin-header-row">
            <div>
                <h1>Управление пользователями</h1>
                <p>Просматривайте пользователей и изменяйте их роли</p>
            </div>

            <div class="admin-breadcrumbs">
                <a href="index.php">⌂ Главная</a>
                <span>›</span>
                <b>Пользователи</b>
            </div>
        </div>

        <div class="admin-stats-row">

            <div class="admin-stat">
                <div class="stat-icon">👥</div>
                <div>
                    <p>Всего пользователей</p>
                    <strong><?= $totalUsers ?></strong>
                    <small>в системе</small>
                </div>
            </div>

            <div class="admin-stat">
                <div class="stat-icon">♕</div>
                <div>
                    <p>Администраторов</p>
                    <strong><?= $adminsCount ?></strong>
                    <small>с полным доступом</small>
                </div>
            </div>

            <div class="admin-stat">
                <div class="stat-icon">▦</div>
                <div>
                    <p>Менеджеров</p>
                    <strong><?= $managersCount ?></strong>
                    <small>управление складом</small>
                </div>
            </div>

        </div>

        <section class="admin-products-panel user-panel">

            <?php if ($message): ?>
                <div class="admin-message"><?= $message ?></div>
            <?php endif; ?>

            <div class="admin-panel-actions">
                <a href="register.php" class="admin-add-product">
                    <span>＋</span>
                    Добавить пользователя
                </a>
            </div>

            <div class="admin-table-wrap">

                <table class="admin-products-table users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Роль</th>
                            <th>Изменить роль</th>
                            <th>Действие</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>

                                <td class="admin-product-name">
                                    <?= htmlspecialchars($user['name']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($user['email']) ?>
                                </td>

                                <td>
                                    <span class="role-badge">
                                        <?= htmlspecialchars($user['role']) ?>
                                    </span>
                                </td>

                                <td>
                                    <form method="POST" class="admin-inline-form">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

                                        <select name="role">
                                            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>
                                                Пользователь
                                            </option>

                                            <option value="manager" <?= $user['role'] === 'manager' ? 'selected' : '' ?>>
                                                Менеджер
                                            </option>

                                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>
                                                Администратор
                                            </option>
                                        </select>

                                        <button type="submit">
                                            Сохранить
                                        </button>
                                    </form>
                                </td>

                                <td>
                                    <?php if ($user['id'] !== $_SESSION['user']['id']): ?>
                                        <a 
                                            href="admin_users.php?delete=<?= $user['id'] ?>" 
                                            class="admin-delete-text"
                                            onclick="return confirm('Удалить пользователя?')"
                                        >
                                            Удалить
                                        </a>
                                    <?php else: ?>
                                        <span class="admin-current-user">
                                            Текущий пользователь
                                        </span>
                                    <?php endif; ?>
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