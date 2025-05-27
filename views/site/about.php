<?php
/** @var yii\web\View $this */
/** @var app\models\Book $book */
/** @var array $lines */
/** @var int $currentPage */
/** @var int $totalPages */
/** @var int $screenWidth */
/** @var int $screenHeight */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Читалка - ' . $book->title;
?>

<!-- Подключаем Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="reader-container">
    <!-- Кнопка возврата на главную -->
    <a href="/account" class="nav-button home-button position-fixed" style="top: 20px; right: 20px; z-index: 9999;" data-bs-toggle="tooltip" data-bs-placement="left" title="Вернуться на главную">
        <i class="fa-solid fa-arrow-left"></i>
    </a>

    <!-- Кнопка показа/скрытия панели управления -->
    <button id="toggle-controls" class="nav-button settings-button position-fixed" style="top: 20px; left: 20px; z-index: 9999;" data-bs-toggle="tooltip" data-bs-placement="right" title="Настройки чтения">
        <i class="fa-solid fa-sliders"></i>
    </button>

    <!-- Контейнер для текста -->
    <div class="position-relative">
        <a href="<?= Url::to(['site/read', 'id' => $book->id, 'page' => $currentPage - 1, 'width' => $screenWidth, 'height' => $screenHeight]) ?>" 
           class="carousel-control-prev" 
           id="prev-page" 
           <?= $currentPage <= 1 ? 'style="display: none;"' : '' ?>>
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Предыдущая</span>
        </a>
        
        <div id="reader-content" class="reader-content p-4">
            <?php if (!empty($lines)): ?>
                <?php foreach ($lines as $line): ?>
                    <div class="book-line"><?= Html::encode(trim($line)) ?></div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Текст книги пуст</p>
            <?php endif; ?>
        </div>

        <a href="<?= Url::to(['site/read', 'id' => $book->id, 'page' => $currentPage + 1, 'width' => $screenWidth, 'height' => $screenHeight]) ?>" 
           class="carousel-control-next" 
           id="next-page" 
           <?= $currentPage >= $totalPages ? 'style="display: none;"' : '' ?>>
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Следующая</span>
        </a>

        <!-- Индикатор страниц -->
        <div class="text-center mt-2">
            <span>Страница <span id="current-page"><?= $currentPage ?></span> из <span id="total-pages"><?= $totalPages ?></span></span>
        </div>
    </div>

    <!-- Панель управления -->
    <div class="control-panel" style="display: none;">
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <div class="btn-group" role="group">
                <button class="btn btn-outline-primary" id="font-size-decrease">A-</button>
                <button class="btn btn-outline-primary" id="font-size-increase">A+</button>
                
                <div class="dropdown d-inline-block">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="fontDropdown" data-bs-toggle="dropdown">
                        Шрифт
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-font="Arial">Arial</a></li>
                        <li><a class="dropdown-item" href="#" data-font="Times New Roman">Times New Roman</a></li>
                        <li><a class="dropdown-item" href="#" data-font="Georgia">Georgia</a></li>
                    </ul>
                </div>
            </div>

            <div class="btn-group" role="group">
                <button class="btn btn-outline-primary theme-toggle" id="dark-theme">🌑</button>
                <button class="btn btn-outline-primary theme-toggle" id="light-theme">🌕</button>
            </div>

            <div class="btn-group" role="group">
                <button class="btn btn-outline-primary" id="contrast-decrease">Контраст -</button>
                <button class="btn btn-outline-primary" id="contrast-increase">Контраст +</button>
            </div>
        </div>
    </div>
</div>

<style>
.reader-container {
    width: 100vw;
    min-height: 100%;
    margin: 0 auto;
    padding: 0;
    background: #fff;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 9999;
    color: #000;
    overflow-y: auto;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

/* Добавляем затемняющий фон */
.reader-container::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    min-height: 100vh;
    background: rgba(0, 0, 0, 0.5);
    z-index: -1;
}

.dark-mode .reader-container::before {
    background: rgba(0, 0, 0, 0.7);  /* Более темный фон для темной темы */
}

.reader-container.dark-mode {
    background: #2a2a2a;
    color: #e0e0e0;
}

.reader-content {
    flex: 1;
    line-height: 1.8;
    font-size: 16px;
    background: #fff;
    transition: all 0.3s ease;
    overflow-y: auto;
    color: #000;
    padding-bottom: 70px;
    width: 100%;
    margin: 0 auto;
    text-align: justify;
    position: relative;
    max-width: 800px;
}

.dark-mode .reader-content {
    background: #2a2a2a;
    color: #e0e0e0;
}

.reader-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 100px;
    background: linear-gradient(to bottom, rgba(255,255,255,0.9), rgba(255,255,255,0));
    pointer-events: none;
    z-index: 1;
}

.reader-content::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100px;
    background: linear-gradient(to top, rgba(255,255,255,0.9), rgba(255,255,255,0));
    pointer-events: none;
    z-index: 1;
}

.dark-mode .reader-content::before {
    background: linear-gradient(to bottom, rgba(42,42,42,0.9), rgba(42,42,42,0));
}

.dark-mode .reader-content::after {
    background: linear-gradient(to top, rgba(42,42,42,0.9), rgba(42,42,42,0));
}

.book-line {
    margin-bottom: 0.8em;
    line-height: 1.6;
    text-align: justify;
    font-size: 1.1em;
    position: relative;
    padding: 0.5em 2em;
    transition: all 0.3s ease;
    width: 100%;
    box-sizing: border-box;
    animation: slideIn 0.5s ease forwards;
    opacity: 0;
}

.book-line:hover {
    background: rgba(0,0,0,0.02);
    transform: translateX(5px) scale(1.01);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.dark-mode .book-line:hover {
    background: rgba(255,255,255,0.05);
    box-shadow: 0 2px 8px rgba(255,255,255,0.1);
}

.book-line::first-letter {
    font-size: 1.2em;
    font-weight: bold;
    color: #666;
}

.dark-mode .book-line::first-letter {
    color: #999;
}

.carousel-control-prev,
.carousel-control-next {
    position: fixed;
    top: 50%;
    transform: translateY(-50%);
    width: 50px;
    height: 50px;
    background-color: rgba(0,0,0,0.5);
    border-radius: 50%;
    border: none;
    z-index: 1000;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    opacity: 0.5;
}

.carousel-control-prev {
    left: 20px;
}

.carousel-control-next {
    right: 20px;
}

.carousel-control-prev:hover,
.carousel-control-next:hover {
    background-color: rgba(0,0,0,0.7);
    transform: translateY(-50%) scale(1.1);
    opacity: 1;
}

.dark-mode .carousel-control-prev,
.dark-mode .carousel-control-next {
    background-color: rgba(255,255,255,0.2);
}

.dark-mode .carousel-control-prev:hover,
.dark-mode .carousel-control-next:hover {
    background-color: rgba(255,255,255,0.3);
}

.control-panel {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 15px;
    border-top: 1px solid #eee;
    background: #f8f9fa;
    transition: all 0.3s ease;
    z-index: 10000;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.dark-mode .control-panel {
    border-top: 1px solid #3a3a3a;
    background: rgba(42,42,42,0.9);
    box-shadow: 0 -2px 10px rgba(0,0,0,0.2);
}

.btn-group {
    margin: 0 5px;
}

.btn-outline-primary {
    border-color: #007bff;
    color: #007bff;
    background: transparent;
}

.dark-mode .btn-outline-primary {
    border-color: #0d6efd;
    color: #0d6efd;
    background: transparent;
}

.btn-outline-primary:hover {
    background-color: #007bff;
    color: #fff;
}

.dark-mode .btn-outline-primary:hover {
    background-color: #0d6efd;
    color: #fff;
}

.dropdown-menu {
    background: #fff;
    transition: all 0.3s ease;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.dark-mode .dropdown-menu {
    background: rgba(42,42,42,0.95);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.dropdown-item {
    color: #000;
    transition: all 0.3s ease;
    padding: 0.5em 1em;
}

.dark-mode .dropdown-item {
    color: #e0e0e0;
}

.dropdown-item:hover {
    background: #f8f9fa;
    transform: translateX(5px);
}

.dark-mode .dropdown-item:hover {
    background: #3a3a3a;
    color: #fff;
}

.home-button {
    opacity: 0.3;
    transition: all 0.3s ease;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.home-button:hover {
    opacity: 1;
    transform: scale(1.1);
    background: rgba(0,0,0,0.1);
}

.dark-mode .home-button:hover {
    background: rgba(255,255,255,0.1);
}

/* Стилизация скроллбара */
.reader-content::-webkit-scrollbar {
    width: 12px;
}

.reader-content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 6px;
}

.reader-content::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 6px;
    border: 3px solid #f1f1f1;
    transition: all 0.3s ease;
}

.reader-content::-webkit-scrollbar-thumb:hover {
    background-color: #666;
}

/* Темная тема для скроллбара */
.dark-mode .reader-content::-webkit-scrollbar-track {
    background: #3a3a3a;
}

.dark-mode .reader-content::-webkit-scrollbar-thumb {
    background-color: #555;
    border: 3px solid #3a3a3a;
}

.dark-mode .reader-content::-webkit-scrollbar-thumb:hover {
    background-color: #666;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.book-line:nth-child(odd) {
    animation-delay: calc(var(--animation-order) * 0.1s);
}

.book-line:nth-child(even) {
    animation-delay: calc(var(--animation-order) * 0.1s + 0.05s);
}

.book-line:hover {
    background: rgba(0,0,0,0.02);
    transform: translateX(5px) scale(1.01);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.dark-mode .book-line:hover {
    background: rgba(255,255,255,0.05);
    box-shadow: 0 2px 8px rgba(255,255,255,0.1);
}

/* Адаптивность для мобильных устройств */
@media (max-width: 768px) {
    .reader-container {
        max-width: 100%;
    }
    
    .reader-content {
        max-width: 100%;
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 40px;
        height: 40px;
    }

    .carousel-control-prev {
        left: 10px;
    }

    .carousel-control-next {
        right: 10px;
    }

    .book-line {
        font-size: 1em;
        line-height: 1.4;
        margin-bottom: 0.6em;
        padding: 0.3em 0.8em;
    }

    .btn-group {
        width: 100%;
        margin-bottom: 0.5rem;
    }

    .home-button {
        top: 5px !important;
        right: 5px !important;
        padding: 0.25rem 0.5rem;
    }
}

@media (max-width: 480px) {
    .reader-content {
        padding: 1rem;
    }
}

/* Стили для кнопок навигации */
.nav-button {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.95);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    color: #007bff;
    font-size: 1.2em;
    text-decoration: none;
    position: relative;
    overflow: hidden;
}

.nav-button i {
    transition: all 0.3s ease;
}

.nav-button:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    background: #007bff;
    color: #fff;
}

.nav-button:hover i {
    transform: scale(1.2);
}

.nav-button:active {
    transform: scale(0.95);
}

/* Стили для кнопки настроек */
.settings-button {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.4);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(0, 123, 255, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
    }
}

/* Стили для темной темы */
.dark-mode .nav-button {
    background: rgba(255, 255, 255, 0.95);
    color: #0d6efd;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
}

.dark-mode .nav-button:hover {
    background: #0d6efd;
    color: #fff;
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.4);
}

/* Эффект пульсации при наведении */
.nav-button::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: rgba(0, 123, 255, 0.2);
    transform: scale(0);
    transition: transform 0.3s ease;
}

.nav-button:hover::after {
    transform: scale(1.5);
    opacity: 0;
}

.dark-mode .nav-button::after {
    background: rgba(13, 110, 253, 0.2);
}

/* Добавляем обводку для лучшей видимости */
.nav-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 50%;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.nav-button:hover::before {
    border-color: currentColor;
}

/* Стили для подсказок */
.tooltip {
    font-size: 0.9em;
    opacity: 0.9;
}

.tooltip-inner {
    background-color: rgba(0, 0, 0, 0.8);
    padding: 8px 12px;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.dark-mode .tooltip-inner {
    background-color: rgba(255, 255, 255, 0.9);
    color: #000;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Добавляем порядок анимации для каждой строки
    document.querySelectorAll('.book-line').forEach((line, index) => {
        line.style.setProperty('--animation-order', index);
    });

    // Добавляем эффект появления текста при прокрутке
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateX(0)';
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '50px'
    });

    document.querySelectorAll('.book-line').forEach(line => {
        observer.observe(line);
    });

    // Добавляем анимацию при изменении размера окна
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            document.querySelectorAll('.book-line').forEach((line, index) => {
                line.style.setProperty('--animation-order', index);
            });
        }, 250);
    });

    // Управление размером шрифта
    let fontSize = parseInt(localStorage.getItem('fontSize')) || 16;
    const content = document.querySelector('.reader-content');
    content.style.fontSize = fontSize + 'px';

    document.getElementById('font-size-increase').addEventListener('click', () => {
        fontSize = Math.min(fontSize + 2, 32);
        content.style.fontSize = fontSize + 'px';
        localStorage.setItem('fontSize', fontSize);
    });

    document.getElementById('font-size-decrease').addEventListener('click', () => {
        fontSize = Math.max(fontSize - 2, 12);
        content.style.fontSize = fontSize + 'px';
        localStorage.setItem('fontSize', fontSize);
    });

    // Управление шрифтами
    document.querySelectorAll('[data-font]').forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const font = e.target.dataset.font;
            content.style.fontFamily = font;
            localStorage.setItem('fontFamily', font);
        });
    });

    // Переключение темы
    const container = document.querySelector('.reader-container');
    const isDarkMode = localStorage.getItem('darkMode') === 'true';
    
    if (isDarkMode) {
        container.classList.add('dark-mode');
    }

    document.getElementById('dark-theme').addEventListener('click', () => {
        container.classList.add('dark-mode');
        localStorage.setItem('darkMode', 'true');
    });

    document.getElementById('light-theme').addEventListener('click', () => {
        container.classList.remove('dark-mode');
        localStorage.setItem('darkMode', 'false');
    });

    // Управление контрастом
    let contrast = parseInt(localStorage.getItem('contrast')) || 100;
    content.style.filter = `contrast(${contrast}%)`;

    document.getElementById('contrast-increase').addEventListener('click', () => {
        contrast = Math.min(contrast + 10, 150);
        content.style.filter = `contrast(${contrast}%)`;
        localStorage.setItem('contrast', contrast);
    });

    document.getElementById('contrast-decrease').addEventListener('click', () => {
        contrast = Math.max(contrast - 10, 50);
        content.style.filter = `contrast(${contrast}%)`;
        localStorage.setItem('contrast', contrast);
    });

    // Переключение видимости панели управления
    const toggleButton = document.getElementById('toggle-controls');
    const controlPanel = document.querySelector('.control-panel');
    
    if (localStorage.getItem('controlPanelVisible') === 'true') {
        controlPanel.style.display = 'block';
    }

    toggleButton.addEventListener('click', () => {
        if (controlPanel.style.display === 'none') {
            controlPanel.style.display = 'block';
            localStorage.setItem('controlPanelVisible', 'true');
        } else {
            controlPanel.style.display = 'none';
            localStorage.setItem('controlPanelVisible', 'false');
        }
    });

    // Инициализация подсказок
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            trigger: 'hover',
            animation: true
        });
    });
});
</script>