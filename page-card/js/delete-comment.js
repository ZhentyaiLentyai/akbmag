document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.comment-delete-button');
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                // Получаем id товара из атрибута data-id кнопки
                const commentId = this.closest('.comment-item').getAttribute('data-id');
    
                // Запрос на сервер для получения данных о товаре
                fetch(`../php/delete-comment.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${commentId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Удаляем весь блок комментария, если удаление прошло успешно
                        this.closest('.comment-item').remove();
                    } else {
                        console.error('Ошибка:', data.message);
                    }
                })
                .catch(error => console.error('Ошибка:', error));
                    
            });
        });
    });