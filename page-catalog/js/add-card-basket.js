document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.card-button__basket');
    
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            event.preventDefault();
            const productId = this.getAttribute('data-id');

            fetch(`../../page-catalog/php/add-card-basket.php?id=${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showMessage('Товар добавлен в корзину'); // Показать сообщение об успехе
                    } else {
                        showMessage('Требуется авторизоваться');
                    }
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                    showMessage('Ошибка при добавлении товара в корзину');
                });
        });
    });

    // Функция для отображения всплывающего сообщения
    function showMessage(message) {
        // Создаем элемент для сообщения
        const messageBox = document.createElement('div');
        messageBox.classList.add('message-box'); // Добавим CSS-класс для оформления
        messageBox.textContent = message;

        // Добавляем сообщение на страницу
        document.body.appendChild(messageBox);

        // Удаляем сообщение через 3 секунды
        setTimeout(() => {
            messageBox.remove();
        }, 3000);
    }
});