<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $message = "Заполните все поля";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["user"] = $user;

            if ($user["role"] === "admin") {
                header("Location: admin.php");
            } elseif ($user["role"] === "manager") {
                header("Location: manager_stock.php");
            } else {
                header("Location: profile.php");
            }
            exit;
        } else {
            $message = "Неверный email или пароль";
        }
    }
}

include "includes/header.php";
?>

<div class="login-premium-page">

    <div class="login-premium-box">

        <h1>Авторизация</h1>

        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">

            <div class="login-input-wrap">
                <span>♙</span>
                <input 
                    type="email" 
                    name="email" 
                    placeholder="Email"
                    required
                >
            </div>

            <div class="login-input-wrap">
                <span>▢</span>
                <input 
                    type="password" 
                    name="password" 
                    placeholder="Пароль"
                    required
                >
            </div>

            <div class="login-buttons-row">
                <button type="submit" class="login-main-btn">
                    Войти
                </button>

                <a href="register.php" class="login-register-btn">
                    Зарегистрироваться
                </a>
            </div>

        </form>

    </div>

</div>

<?php include "includes/footer.php"; ?>