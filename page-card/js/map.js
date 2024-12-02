ymaps.ready(function () {
    var myMap = new ymaps.Map("map", {
        center: [55.751574, 37.573856], // Центр карты (Москва)
        zoom: 5 // Масштаб карты
    });

    // Создаем кастомную метку
    var myPlacemarkMsk = new ymaps.Placemark(
        [55.753215, 37.622504], // Координаты метки
        {
            hintContent: "Москва", // Подсказка при наведении
        },
        {
            // Опции метки
            iconLayout: 'default#image', // Указываем, что будет использоваться изображение
            iconImageHref: 'https://cdn-icons-png.flaticon.com/512/684/684908.png', // URL иконки
            iconImageSize: [40, 40], // Размер иконки
            iconImageOffset: [-20, -40] // Смещение иконки, чтобы центр совпадал с координатами
        }
    );
    var myPlacemarkSpb = new ymaps.Placemark(
        [59.934280, 30.335099], // Координаты метки
        {
            hintContent: "Санкт-Петербург", // Подсказка при наведении
        },
        {
            // Опции метки
            iconLayout: 'default#image', // Указываем, что будет использоваться изображение
            iconImageHref: 'https://cdn-icons-png.flaticon.com/512/684/684908.png', // URL иконки
            iconImageSize: [40, 40], // Размер иконки
            iconImageOffset: [-20, -40] // Смещение иконки, чтобы центр совпадал с координатами
        }
    );

    // Добавляем метку на карту
    myMap.geoObjects.add(myPlacemarkMsk);
    myMap.geoObjects.add(myPlacemarkSpb);
});