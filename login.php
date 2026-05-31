<?php

session_start();
require_once "config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $pdo->prepare(
        "SELECT * FROM users WHERE email = ?"
    );

    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(
        $user &&
        password_verify(
            $password,
            $user['password']
        )
    ){

        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'role' => $user['role']
        ];

        if($user['role'] === 'admin'){
            header("Location: admin.php");
        }
        else{
            header("Location: profile.php");
        }

        exit;

    } else {

        $message = "Неверный логин или пароль";
    }
}

include "includes/header.php";
?>

<div class="auth-container">

    <h2>Авторизация</h2>

    <?php if($message): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">

        <div class="form-group">
            <input
                type="email"
                name="email"
                placeholder="Email"
                required>
        </div>

        <div class="form-group">
            <input
                type="password"
                name="password"
                placeholder="Пароль"
                required>
        </div>

        <button type="submit">
            Войти
        </button>

    </form>

</div>

<?php include "includes/footer.php"; ?>