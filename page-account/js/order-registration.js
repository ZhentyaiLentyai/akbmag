document.addEventListener("DOMContentLoaded", function() {
    // Открытие модального окна
    document.getElementById('form-order').onclick = function() {
        document.getElementById('orderRegistration').style.display = 'block';
    };

    // Закрытие модального окна
    document.querySelector('.close-orderRegistration').onclick = function() {
        document.getElementById('orderRegistration').style.display = 'none';
    };

    // Закрытие модального окна при клике вне его
    window.onclick = function(event) {
        const modal = document.getElementById('orderRegistration');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };
});
