<?php
/** @var yii\web\View $this */

$this->title = 'Читалка';
?>

<div class="reader-container mt-5"> <!-- Добавлен класс mt-5 для отступа сверху -->
    <!-- Панель управления -->
    <div class="control-panel mb-3">
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

        <div class="btn-group ms-2" role="group">
            <button class="btn btn-outline-primary" id="brightness-decrease">🌑</button>
            <button class="btn btn-outline-primary" id="brightness-increase">🌕</button>
        </div>

        <div class="btn-group ms-2" role="group">
            <button class="btn btn-outline-primary" id="contrast-decrease">Контраст -</button>
            <button class="btn btn-outline-primary" id="contrast-increase">Контраст +</button>
        </div>
    </div>

    <!-- Контейнер для текста -->
    <div id="reader-content" class="reader-content p-4">
        <!-- Здесь будет текст книги -->
        <div id="page-content">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
        </div>
    </div>

    <!-- Навигация по страницам -->
    <div class="pagination-controls mt-3 text-center">
        <button class="btn btn-primary" id="prev-page">← Предыдущая</button>
        <span class="mx-3">Страница <span id="current-page">1</span> из <span id="total-pages">10</span></span>
        <button class="btn btn-primary" id="next-page">Следующая →</button>
    </div>
</div>

<style>
.reader-container {
    max-width: 800px;
    margin: 0 auto;
    background: #fff;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    position: relative; /* Добавлено */
    z-index: 1; /* Добавлено */
    color:black;
}

.reader-content {
    min-height: 500px;
    line-height: 1.6;
    font-size: 16px;
    background: #fff;
    transition: all 0.3s ease;
}

.control-panel {
    padding: 15px;
    border-bottom: 1px solid #eee;
    background: #f8f9fa;
    position: sticky; /* Добавлено */
    top: 0; /* Добавлено */
    z-index: 2; /* Добавлено */
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let fontSize = 16;
    let brightness = 100;
    let contrast = 100;
    const content = document.getElementById('reader-content');
    
    // Управление размером шрифта
    document.getElementById('font-size-increase').addEventListener('click', () => {
        fontSize = Math.min(fontSize + 2, 32);
        content.style.fontSize = fontSize + 'px';
    });

    document.getElementById('font-size-decrease').addEventListener('click', () => {
        fontSize = Math.max(fontSize - 2, 12);
        content.style.fontSize = fontSize + 'px';
    });

    // Управление шрифтами
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', (e) => {
            content.style.fontFamily = e.target.dataset.font;
        });
    });

    // Управление яркостью
    document.getElementById('brightness-increase').addEventListener('click', () => {
        brightness = Math.min(brightness + 10, 150);
        updateFilters();
    });

    document.getElementById('brightness-decrease').addEventListener('click', () => {
        brightness = Math.max(brightness - 10, 50);
        updateFilters();
    });

    // Управление контрастом
    document.getElementById('contrast-increase').addEventListener('click', () => {
        contrast = Math.min(contrast + 10, 150);
        updateFilters();
    });

    document.getElementById('contrast-decrease').addEventListener('click', () => {
        contrast = Math.max(contrast - 10, 50);
        updateFilters();
    });

    function updateFilters() {
        content.style.filter = `brightness(${brightness}%) contrast(${contrast}%)`;
    }

    // Навигация по страницам
    document.getElementById('prev-page').addEventListener('click', () => {
        let currentPage = parseInt(document.getElementById('current-page').textContent);
        if (currentPage > 1) {
            document.getElementById('current-page').textContent = currentPage - 1;
            // Здесь должен быть код загрузки предыдущей страницы
        }
    });

    document.getElementById('next-page').addEventListener('click', () => {
        let currentPage = parseInt(document.getElementById('current-page').textContent);
        let totalPages = parseInt(document.getElementById('total-pages').textContent);
        if (currentPage < totalPages) {
            document.getElementById('current-page').textContent = currentPage + 1;
            // Здесь должен быть код загрузки следующей страницы
        }
    });
});
</script>
