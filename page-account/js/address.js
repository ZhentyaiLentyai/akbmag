document.addEventListener('DOMContentLoaded', () => {
    const citySelect = document.getElementById('order_city');
    const deliveryAddress = document.getElementById('delivery-address');

    // Список адресов для городов
    const addresses = {
        'Москва': 'Улица Тверская, дом 1',
        'Санкт-Петербург': 'Невский проспект, дом 20'
    };

    // Обработчик события изменения города
    citySelect.addEventListener('change', (event) => {
        const selectedCity = event.target.value;

        // Отображаем адрес для выбранного города
        if (addresses[selectedCity]) {
            deliveryAddress.textContent = `Адрес получения: ${addresses[selectedCity]}`;
        } else {
            deliveryAddress.textContent = '1'; // Если ничего не выбрано
        }
    });
});
