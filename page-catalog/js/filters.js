document.addEventListener("DOMContentLoaded", function () {
    const slider = document.getElementById('price-slider');
    const priceMinLabel = document.getElementById('price-min-label');
    const priceMaxLabel = document.getElementById('price-max-label');
    const categoryFilter = document.getElementById('category-filter');
    const availabilityMsk = document.getElementById('availability-msk');
    const availabilitySpb = document.getElementById('availability-spb');
    const resetFilters = document.getElementById('reset-filters');
    const cardLinks = document.querySelectorAll('.card__link');

    // Инициализация noUiSlider
    noUiSlider.create(slider, {
        start: [0, 20000], // Начальные значения
        connect: true, // Полоса между бегунками
        range: {
            min: 0,
            max: 20000 // Максимальная цена
        },
        step: 1, // Шаг изменения
        format: {
            to: function (value) {
                return Math.round(value);
            },
            from: function (value) {
                return Number(value);
            }
        }
    });

    // Обновление меток и фильтрация карточек по цене
    slider.noUiSlider.on('update', function (values) {
        const minPrice = parseInt(values[0]);
        const maxPrice = parseInt(values[1]);

        priceMinLabel.textContent = minPrice;
        priceMaxLabel.textContent = maxPrice;

        applyFilters();
    });

    // Обработчик изменения фильтра по категории
    categoryFilter.addEventListener('change', applyFilters);

    // Обработчики изменения фильтров по наличию
    availabilityMsk.addEventListener('change', applyFilters);
    availabilitySpb.addEventListener('change', applyFilters);

    // Обработчик для кнопки сброса фильтров
    resetFilters.addEventListener('click', function () {
        slider.noUiSlider.set([0, 20000]); // Сбрасываем значения слайдера
        categoryFilter.value = ''; // Сбрасываем фильтр категории
        availabilityMsk.checked = false; // Сбрасываем фильтр наличия в Москве
        availabilitySpb.checked = false; // Сбрасываем фильтр наличия в СПБ
        applyFilters(); // Применяем сброс
    });

    function applyFilters() {
        const minPrice = parseInt(slider.noUiSlider.get()[0]);
        const maxPrice = parseInt(slider.noUiSlider.get()[1]);
        const selectedCategory = categoryFilter.value;
        const mskChecked = availabilityMsk.checked;
        const spbChecked = availabilitySpb.checked;

        cardLinks.forEach(link => {
            const card = link.querySelector('.card');
            const cardPrice = parseFloat(card.dataset.price);
            const cardCategory = card.dataset.category;
            const cardAvailabilityMsk = card.dataset.availability_msk === '1';
            const cardAvailabilitySpb = card.dataset.availability_spb === '1';

            // Проверяем все условия фильтров
            const matchesPrice = cardPrice >= minPrice && cardPrice <= maxPrice;
            const matchesCategory = selectedCategory === '' || cardCategory === selectedCategory;
            const matchesMsk = !mskChecked || cardAvailabilityMsk;
            const matchesSpb = !spbChecked || cardAvailabilitySpb;

            // Скрываем или показываем всю ссылку вместе с карточкой
            if (matchesPrice && matchesCategory && matchesMsk && matchesSpb) {
                link.style.display = 'block'; // Показываем
            } else {
                link.style.display = 'none'; // Скрываем
            }
        });
    }
});
