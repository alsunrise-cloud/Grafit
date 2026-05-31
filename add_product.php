<?php
require_once "config/db.php";
require_once "includes/auth.php";

requireRole(['admin']);

$error = "";

$categories = $pdo->query("
    SELECT id, name 
    FROM categories 
    ORDER BY id
")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $price = (float)$_POST["price"];
    $stock = (int)$_POST["stock"];
    $categoryId = (int)$_POST["category_id"];
    $newCategory = trim($_POST["new_category"]);

    if ($title === "" || $description === "" || $price <= 0 || $stock < 0) {
        $error = "Заполните все обязательные поля корректно.";
    } else {
        if ($newCategory !== "") {
            $stmt = $pdo->prepare("
                INSERT INTO categories (name)
                VALUES (?)
                RETURNING id
            ");
            $stmt->execute([$newCategory]);
            $categoryId = $stmt->fetchColumn();
        }

        if ($categoryId <= 0) {
            $error = "Выберите категорию или добавьте новую.";
        } else {
            $imageName = "";

            if (!empty($_FILES["image"]["name"])) {
                $uploadDir = "assets/images/";

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                $imageName = time() . "_" . uniqid() . "." . $extension;
                $uploadPath = $uploadDir . $imageName;

                move_uploaded_file($_FILES["image"]["tmp_name"], $uploadPath);
            }

            $stmt = $pdo->prepare("
                INSERT INTO products 
                (category_id, title, description, price, stock, image)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $categoryId,
                $title,
                $description,
                $price,
                $stock,
                $imageName
            ]);

            header("Location: admin_products.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ГРАФИТ — Добавить товар</title>
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
            <a href="admin_products.php" class="active"><span>▧</span>Товары</a>
            <a href="manager_stock.php"><span>▦</span>Склад</a>
            <a href="admin_orders.php"><span>🛒</span>Заказы</a>
            <a href="admin_users.php"><span>👥</span>Пользователи</a>
        </nav>

        <a href="logout.php" class="admin-logout-btn">
            <span>↪</span>
            Выход
        </a>

    </aside>

    <main class="admin-content">

        <div class="admin-header-row">
            <div>
                <h1>Добавление товара</h1>
                <p>Заполните информацию о товаре для каталога магазина</p>
            </div>

            <div class="admin-breadcrumbs">
                <a href="index.php">⌂ Главная</a>
                <span>›</span>
                <a href="admin_products.php">Товары</a>
                <span>›</span>
                <b>Добавить товар</b>
            </div>
        </div>

        <section class="admin-products-panel">

            <?php if ($error): ?>
                <div class="admin-message error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="admin-form">

                <div class="admin-form-grid">

                    <div class="admin-form-group">
                        <label>Название товара</label>
                        <input 
                            type="text" 
                            name="title" 
                            placeholder="Например: Ручка Graphite Pro"
                            required
                        >
                    </div>

                    <div class="admin-form-group">
                        <label>Цена</label>
                        <input 
                            type="number" 
                            step="0.01" 
                            name="price" 
                            placeholder="890"
                            required
                        >
                    </div>

                    <div class="admin-form-group">
                        <label>Количество на складе</label>
                        <input 
                            type="number" 
                            name="stock" 
                            min="0"
                            placeholder="10"
                            required
                        >
                    </div>

                    <div class="admin-form-group">
                        <label>Категория</label>
                        <select name="category_id">
                            <option value="0">Выберите категорию</option>

                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>">
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="admin-form-group">
                        <label>Новая категория</label>
                        <input 
                            type="text" 
                            name="new_category" 
                            placeholder="Заполните, если нужной категории нет"
                        >
                    </div>

                    <div class="admin-form-group">
                        <label>Фото товара</label>
                        <input 
                            type="file" 
                            name="image" 
                            accept="image/*"
                        >
                    </div>

                    <div class="admin-form-group full">
                        <label>Описание товара</label>
                        <textarea 
                            name="description" 
                            placeholder="Введите описание товара"
                            required
                        ></textarea>
                    </div>

                </div>

                <div class="admin-form-actions">
                    <button type="submit" class="admin-save-btn">
                        Сохранить товар
                    </button>

                    <a href="admin_products.php" class="admin-cancel-btn">
                        Отмена
                    </a>
                </div>

            </form>

        </section>

    </main>

</div>

</body>
</html>