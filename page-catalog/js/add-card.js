document.addEventListener("DOMContentLoaded", function() {
    // Открытие модального окна
    document.getElementById('addCardButton').onclick = function() {
        document.getElementById('addCardModal').style.display = 'block';
    };

    // Закрытие модального окна
    document.querySelector('.close-button__add-card').onclick = function() {
        document.getElementById('addCardModal').style.display = 'none';
    };

    // Закрытие модального окна при клике вне его
    window.onclick = function(event) {
        const modal = document.getElementById('addCardModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };
});