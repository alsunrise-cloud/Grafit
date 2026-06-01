<?php require_once "includes/auth.php"; ?>
<?php include "includes/header.php"; ?>
<div id="preloader">
    <div class="preloader-content">

        <div class="preloader-logo">
            ♕ ГРАФИТ
        </div>

        <div class="preloader-line"></div>

        <div class="preloader-text">
            Премиальная канцелярия
        </div>

    </div>
</div>

<section class="hero">
    <div class="hero-content">
        <h1>Премиальная канцелярия</h1>
        <p>
            Интернет-магазин редких и качественных канцелярских товаров
            для офиса, организаций и коллекционеров.
        </p>
        <a href="catalog.php" class="btn">Перейти в каталог</a>
    </div>
</section>

<div class="slider-dots">
    <span class="active"></span>
    <span></span>
    <span></span>
</div>

<section class="home-section">
    <h2>Категории товаров</h2>

    <div class="category-grid">

        <a href="catalog.php?category=1" class="category-card">
            <div class="cat-icon">
                <svg viewBox="0 0 64 64">
                    <path d="M14 50 L22 47 L49 20 L42 13 L15 40 Z"/>
                    <path d="M38 17 L47 26"/>
                    <path d="M14 50 L20 44"/>
                </svg>
            </div>
            <p>Письменные<br>принадлежности</p>
        </a>

        <a href="catalog.php?category=2" class="category-card">
            <div class="cat-icon">
                <svg viewBox="0 0 64 64">
                    <rect x="18" y="10" width="30" height="44" rx="4"/>
                    <line x1="26" y1="22" x2="40" y2="22"/>
                    <line x1="26" y1="32" x2="40" y2="32"/>
                    <line x1="26" y1="42" x2="40" y2="42"/>
                </svg>
            </div>
            <p>Ежедневники</p>
        </a>

        <a href="catalog.php?category=3" class="category-card">
            <div class="cat-icon">
                <svg viewBox="0 0 64 64">
                    <rect x="20" y="9" width="28" height="44" rx="4"/>
                    <path d="M26 53 L34 47 L42 53"/>
                    <line x1="27" y1="22" x2="41" y2="22"/>
                    <line x1="27" y1="32" x2="41" y2="32"/>
                    <line x1="27" y1="42" x2="41" y2="42"/>
                </svg>
            </div>
            <p>Блокноты<br>и планеры</p>
        </a>

        <a href="catalog.php?category=4" class="category-card">
            <div class="cat-icon wide-icon">
                <svg viewBox="0 0 80 64">
                    <path d="M16 30 L54 18 L66 29 L28 42 Z"/>
                    <path d="M16 30 L16 40 L28 52 L66 39 L66 29"/>
                    <path d="M28 42 L28 52"/>
                    <line x1="23" y1="32" x2="58" y2="21"/>
                </svg>
            </div>
            <p>Пеналы и<br>аксессуары</p>
        </a>

        <a href="catalog.php?category=5" class="category-card">
            <div class="cat-icon">
                <svg viewBox="0 0 64 64">
                    <rect x="14" y="24" width="36" height="26" rx="3"/>
                    <path d="M22 24 V18 C22 14 26 12 32 12 C38 12 42 14 42 18 V24"/>
                    <line x1="14" y1="32" x2="50" y2="32"/>
                    <line x1="24" y1="24" x2="24" y2="38"/>
                    <line x1="40" y1="24" x2="40" y2="38"/>
                </svg>
            </div>
            <p>Коллекционные<br>наборы</p>
        </a>

        <a href="catalog.php?category=6" class="category-card">
            <div class="cat-icon">
                <svg viewBox="0 0 64 64">
                    <circle cx="32" cy="32" r="22"/>
                    <circle cx="32" cy="32" r="7"/>
                </svg>
            </div>
            <p>Хобби<br>и творчество</p>
        </a>

    </div>
</section>  


<section class="home-section">
    <h2>Наши преимущества</h2>

    <div class="advantages-grid">
        <div class="advantage-card">
            <div class="adv-icon">▣</div>
            <div>
                <h3>Быстрая доставка</h3>
                <p>Доставка по всей России</p>
            </div>
        </div>

        <div class="advantage-card">
            <div class="adv-icon">◇</div>
            <div>
                <h3>Качество товаров</h3>
                <p>Гарантия качества</p>
            </div>
        </div>

        <div class="advantage-card">
            <div class="adv-icon">▭</div>
            <div>
                <h3>Удобная оплата</h3>
                <p>Онлайн-оплата</p>
            </div>
        </div>

        <div class="advantage-card">
            <div class="adv-icon">☏</div>
            <div>
                <h3>Поддержка 24/7</h3>
                <p>Мы всегда на связи</p>
            </div>
        </div>
    </div>
</section>
<script>

window.addEventListener('load', function() {

    const preloader = document.getElementById('preloader');

    if (!preloader) return;

    setTimeout(function() {

        preloader.style.opacity = '0';

        setTimeout(function() {
            preloader.remove();
        }, 800);

    }, 1500);

});

</script>
<?php include "includes/footer.php"; ?>