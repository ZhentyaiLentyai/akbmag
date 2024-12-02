document.addEventListener("DOMContentLoaded", function() {
    // Открытие модального окна редактирования
    document.querySelectorAll('.card-button__edit').forEach(button => {
        button.onclick = function() {
            event.preventDefault();
            const card = button.closest('.card') || button.closest('.product__content');
            
            // Проверяем, существует ли карточка
            if (!card) {
                console.error("Карточка не найдена.");
                return;
            }

            // Проверяем существование элементов перед использованием
        
            document.getElementById('edit_card_id').value = card.dataset.id || '';
            document.getElementById('edit_name').value = card.dataset.name || '';  
            document.getElementById('edit_price').value = card.dataset.price || '';
            document.getElementById('edit_manufacturer').value = card.dataset.manufacturer || '';
            document.getElementById('edit_availability_msk').value = card.dataset.availability_msk || '1';
            document.getElementById('edit_availability_spb').value = card.dataset.availability_spb || '1';
            document.getElementById('edit_category').value = card.dataset.category || '';
            document.getElementById('edit_weight').value = card.dataset.weight || '';
            document.getElementById('edit_warranty_period').value = card.dataset.warranty_period;

            document.getElementById('editCardModal').style.display = 'block'; // Открываем модальное окно
        };
        
    });

    // Закрытие модального окна
    document.querySelector('.close-button__edit-card').onclick = function() {
        document.getElementById('editCardModal').style.display = 'none';
    };

    // Закрытие модального окна при клике вне его
    window.onclick = function(event) {
        const modal = document.getElementById('editCardModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };
});
