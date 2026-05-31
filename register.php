<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($name === "" || $email === "" || $password === "") {
        $message = "Заполните все поля";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Введите корректный Email";
    } elseif (strlen($password) < 6) {
        $message = "Пароль должен быть минимум 6 символов";
    } else {

        $check = $pdo->prepare("
            SELECT id 
            FROM users 
            WHERE email = ?
        ");
        $check->execute([$email]);

        if ($check->fetch()) {
            $message = "Пользователь с таким Email уже существует";
        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO users (name, email, password, role)
                VALUES (?, ?, ?, ?)
            ");

            $stmt->execute([
                $name,
                $email,
                $hash,
                "user"
            ]);

            $userNumber = $pdo->query("
                SELECT COUNT(*) 
                FROM users
            ")->fetchColumn();

            $_SESSION['welcome_user'] = [
                'name' => $name,
                'number' => $userNumber
            ];

            header("Location: welcome.php");
            exit;
        }
    }
}

include "includes/header.php";
?>

<div class="auth-container">

    <h2>Регистрация</h2>

    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" action="register.php">

        <div class="form-group">
            <input
                type="text"
                name="name"
                placeholder="Ваше имя"
                required
            >
        </div>

        <div class="form-group">
            <input
                type="email"
                name="email"
                placeholder="Email"
                required
            >
        </div>

        <div class="form-group">
            <input
                type="password"
                name="password"
                placeholder="Пароль"
                minlength="6"
                required
            >
        </div>

        <button type="submit" class="btn">
            Зарегистрироваться
        </button>

    </form>

    <p style="margin-top: 18px; color: #c9c9c9;">
        Уже есть аккаунт?
        <a href="login.php" style="color: #d6aa3a;">Войти</a>
    </p>

</div>

<?php include "includes/footer.php"; ?>