<?php

require_once "includes/auth.php";

requireAuth();

include "includes/header.php";
?>

<div class="profile-page">

    <h1>Личный кабинет</h1>

    <div class="profile-card">

        <p>
            <strong>Имя:</strong>
            <?= $_SESSION['user']['name'] ?>
        </p>

        <p>
            <strong>Роль:</strong>
            <?= $_SESSION['user']['role'] ?>
        </p>

        <p>
            <strong>ID:</strong>
            <?= $_SESSION['user']['id'] ?>
        </p>

    </div>

</div>

<?php include "includes/footer.php"; ?>