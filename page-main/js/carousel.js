const carousel = document.querySelector('.carousel__container');
const items = document.querySelectorAll('.carousel__item');
const indicatorsContainer = document.querySelector('.carousel__indicators');
let currentIndex = 0;
let autoSlideInterval;

// Функция для обновления карусели
function updateCarousel() {
    carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
    updateIndicators();
}

// Функция для обновления активной точки
function updateIndicators() {
    document.querySelectorAll('.carousel__indicator').forEach((indicator, index) => {
        indicator.classList.toggle('carousel__indicator--active', index === currentIndex);
    });
}

// Создание точек и добавление обработчиков событий
items.forEach((_, index) => {
    const indicator = document.createElement('div');
    indicator.classList.add('carousel__indicator');
    if (index === currentIndex) indicator.classList.add('carousel__indicator--active');
    
    // При клике на точку, переключаемся на соответствующий слайд
    indicator.addEventListener('click', () => {
        currentIndex = index;
        updateCarousel();
        resetAutoSlide();
    });
    
    indicatorsContainer.appendChild(indicator);
});

// Запуск автоматического переключения слайдов
function startAutoSlide() {
    autoSlideInterval = setInterval(() => {
        currentIndex = (currentIndex + 1) % items.length;
        updateCarousel();
    }, 3000); // Смена слайда каждые 3 секунды
}

// Остановка и перезапуск автоматического переключения
function resetAutoSlide() {
    clearInterval(autoSlideInterval);
    startAutoSlide();
}

// Инициализация карусели
updateCarousel();
startAutoSlide();
