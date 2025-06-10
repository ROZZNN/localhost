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
    <a href="<?= Yii::getAlias('@web') ?>/account" class="nav-button home-button position-fixed" style="top: 20px; right: 20px; z-index: 9999;" data-bs-toggle="tooltip" data-bs-placement="left" title="Вернуться на главную">
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
    background: #ffffff;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 9999;
    color: #000000;
    overflow-y: auto;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

/* Стили для светлой темы */
.reader-container {
    --bg-color: #ffffff;
    --text-color: #000000;
    --hover-bg: rgba(0,0,0,0.02);
    --shadow-color: rgba(0,0,0,0.1);
    --gradient-start: rgba(255,255,255,0.9);
    --gradient-end: rgba(255,255,255,0);
    --first-letter-color: #666666;
}

/* Стили для темной темы */
.reader-container.dark-mode {
    --bg-color: #1a1a1a;
    --text-color: #ffffff;
    --hover-bg: rgba(255,255,255,0.05);
    --shadow-color: rgba(255,255,255,0.1);
    --gradient-start: rgba(26,26,26,0.9);
    --gradient-end: rgba(26,26,26,0);
    --first-letter-color: #cccccc;
}

.reader-container {
    background: var(--bg-color);
    color: var(--text-color);
}

.reader-content {
    flex: 1;
    line-height: 1.8;
    font-size: 16px;
    background: var(--bg-color);
    transition: all 0.3s ease;
    overflow-y: auto;
    color: var(--text-color);
    padding-bottom: 70px;
    width: 100%;
    margin: 0 auto;
    text-align: justify;
    position: relative;
    max-width: 800px;
}

.reader-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 100px;
    background: linear-gradient(to bottom, var(--gradient-start), var(--gradient-end));
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
    background: linear-gradient(to top, var(--gradient-start), var(--gradient-end));
    pointer-events: none;
    z-index: 1;
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
    background: var(--hover-bg);
    transform: translateX(5px) scale(1.01);
    box-shadow: 0 2px 8px var(--shadow-color);
}

.book-line::first-letter {
    font-size: 1.2em;
    font-weight: bold;
    color: var(--first-letter-color);
}

/* Стили для панели управления */
.control-panel {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: var(--bg-color);
    padding: 1rem;
    box-shadow: 0 -2px 10px var(--shadow-color);
    z-index: 1000;
    transition: all 0.3s ease;
}

.control-panel .btn-group {
    margin: 0 0.5rem;
}

.control-panel .btn {
    border-color: var(--text-color);
    color: var(--text-color);
    background: transparent;
    transition: all 0.3s ease;
}

.control-panel .btn:hover {
    background: var(--hover-bg);
    border-color: var(--text-color);
    color: var(--text-color);
}

.control-panel .dropdown-menu {
    background: var(--bg-color);
    border-color: var(--text-color);
}

.control-panel .dropdown-item {
    color: var(--text-color);
}

.control-panel .dropdown-item:hover {
    background: var(--hover-bg);
    color: var(--text-color);
}

/* Стили для кнопок навигации */
.nav-button {
    background: var(--bg-color);
    color: var(--text-color);
    border: 1px solid var(--text-color);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.nav-button:hover {
    background: var(--hover-bg);
    transform: scale(1.1);
}

/* Анимации */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Медиа-запросы для адаптивности */
@media (max-width: 768px) {
    .control-panel {
        padding: 0.5rem;
    }
    
    .control-panel .btn-group {
        margin: 0.25rem;
    }
    
    .control-panel .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.9rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Функция для сохранения настроек в localStorage
    function saveSettings(settings) {
        localStorage.setItem('readerSettings', JSON.stringify(settings));
    }

    // Функция для загрузки настроек из localStorage
    function loadSettings() {
        const settings = JSON.parse(localStorage.getItem('readerSettings')) || {
            fontSize: 16,
            fontFamily: 'Arial',
            theme: 'light',
            contrast: 100
        };
        return settings;
    }

    // Применение настроек
    function applySettings(settings) {
        const readerContent = document.getElementById('reader-content');
        const readerContainer = document.querySelector('.reader-container');
        
        readerContent.style.fontSize = settings.fontSize + 'px';
        readerContent.style.fontFamily = settings.fontFamily;
        readerContent.style.filter = `contrast(${settings.contrast}%)`;
        
        // Переключаем тему
        if (settings.theme === 'dark') {
            readerContainer.classList.add('dark-mode');
        } else {
            readerContainer.classList.remove('dark-mode');
        }
    }

    // Загрузка сохраненных настроек
    const settings = loadSettings();
    applySettings(settings);

    // Обработчики событий для кнопок
    document.getElementById('font-size-decrease').addEventListener('click', function() {
        settings.fontSize = Math.max(12, settings.fontSize - 2);
        applySettings(settings);
        saveSettings(settings);
    });

    document.getElementById('font-size-increase').addEventListener('click', function() {
        settings.fontSize = Math.min(24, settings.fontSize + 2);
        applySettings(settings);
        saveSettings(settings);
    });

    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            settings.fontFamily = this.dataset.font;
            applySettings(settings);
            saveSettings(settings);
        });
    });

    document.getElementById('dark-theme').addEventListener('click', function() {
        settings.theme = 'dark';
        applySettings(settings);
        saveSettings(settings);
    });

    document.getElementById('light-theme').addEventListener('click', function() {
        settings.theme = 'light';
        applySettings(settings);
        saveSettings(settings);
    });

    document.getElementById('contrast-decrease').addEventListener('click', function() {
        settings.contrast = Math.max(50, settings.contrast - 10);
        applySettings(settings);
        saveSettings(settings);
    });

    document.getElementById('contrast-increase').addEventListener('click', function() {
        settings.contrast = Math.min(150, settings.contrast + 10);
        applySettings(settings);
        saveSettings(settings);
    });

    // Переключение панели управления
    document.getElementById('toggle-controls').addEventListener('click', function() {
        const controlPanel = document.querySelector('.control-panel');
        controlPanel.style.display = controlPanel.style.display === 'none' ? 'block' : 'none';
    });
});
</script>