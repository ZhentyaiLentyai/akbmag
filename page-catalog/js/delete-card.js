document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.card-button__delete').forEach(button => {
        button.addEventListener('click', async function(event) {
            event.preventDefault();

            const cardLink = this.closest('.card__link');
            const card = this.closest('.card');
            const cardId = card.getAttribute('data-id');

            try {
                const response = await fetch('../php/delete-card.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${cardId}`
                });

                const data = await response.json();
                if (data.status === 'success') {
                    card.remove(); // Удаление карточки из DOM
                    cardLink.remove();
                    
                } else {
                    alert('Ошибка при удалении карточки: ' + data.message);
                }
            } catch (error) {
                console.error('Ошибка:', error);
            }
        });
    });
});
