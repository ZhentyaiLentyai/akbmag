document.addEventListener('DOMContentLoaded', function () {
    const selectedCityElement = document.getElementById('selected-city');
    const contactsPhone = document.querySelector('.menu__phone');
    const title = document.getElementById('callback-block__title');
    const deliveryTime = document.getElementById('delivery-time');
    const deliveryCost = document.getElementById('delivery-cost');
    const deliveryDistance = document.getElementById('delivery-distance');

    // Обновляем информацию о городе и отображаем нужное наличие товара
    function updateCityInfo(city) {
        selectedCityElement.textContent = city;

        if(city === 'Москва'){
            contactsPhone.textContent = '+7 (977) 777-77-77';
            if(title) title.textContent = 'Аккумуляторы для автомобиля в Москве';
            if(deliveryTime) deliveryTime.textContent = 'Экспресс по Москве от 50 мин';
            if(deliveryCost) deliveryCost.textContent = '— 300₽ до 10км от МКАД';
            if(deliveryDistance) deliveryDistance.textContent = '— Доставка до 30 км от МКАД';
        } else if(city === 'Санкт-Петербург'){
            contactsPhone.textContent = '+7 (978) 178-78-78';
            if(title) title.textContent = 'Аккумуляторы для автомобиля в Санкт-Петербурге';
            if(deliveryTime) deliveryTime.textContent = 'Экспресс по Санкт-Петербургу от 30 мин';
            if(deliveryCost) deliveryCost.textContent = '— 250₽ до 10км от КАД';
            if(deliveryDistance) deliveryDistance.textContent = '— Доставка до 20 км от КАД';
        } 

        // Скрываем/показываем наличие для выбранного города
        document.querySelectorAll('.availability').forEach(function(el) {
            el.style.display = 'none'; // скрываем все блоки наличия
        });

        if (city === 'Москва') {
            document.querySelectorAll('.availability-msk').forEach(el => el.style.display = 'block');
        } else {
            document.querySelectorAll('.availability-spb').forEach(el => el.style.display = 'block');
        }
    }

    // Проверяем, сохранен ли город в localStorage и обновляем информацию, если да
    const savedCity = localStorage.getItem('selectedCity');
    if (savedCity) {
        updateCityInfo(savedCity);
    } else {
        // Если города нет в localStorage, устанавливаем Москву по умолчанию
        updateCityInfo('Москва');
    }

    // Навешиваем обработчик события на каждый элемент списка городов
    document.querySelectorAll('.navigation__city-option').forEach(function(cityOption) {
        cityOption.addEventListener('click', function(event) {
            event.preventDefault();

            // Получаем название выбранного города
            const selectedCity = event.target.getAttribute('data-city');
            
            // Сохраняем выбранный город в localStorage
            localStorage.setItem('selectedCity', selectedCity);

            // Обновляем информацию о городе на странице
            updateCityInfo(selectedCity);
        });
    });
});
