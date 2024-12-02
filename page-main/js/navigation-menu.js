document.addEventListener("DOMContentLoaded", function () {
    const dropdownBtn = document.querySelector('.navigation__dropdown button');
    const dropdownMenu = document.querySelector('.navigation__dropdown-menu');

    dropdownBtn.addEventListener('click', function (event) {
        event.stopPropagation(); // предотвращаем всплытие события
        // Переключаем видимость выпадающего меню
        dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block"; 
    });
});
