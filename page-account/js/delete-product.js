document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.table-button__delete');
    
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            // Получаем id товара из атрибута data-id кнопки
            const productId = this.closest('tr').getAttribute('data-id');
            const priceCell = this.closest('tr').querySelector('.table-price'); // Ячейка с ценой товара
            const price = parseFloat(priceCell.textContent) || 0; // Цена товара, которую будем вычитать из итоговой суммы

            // Запрос на сервер для удаления товара
            fetch(`../php/delete-product.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${productId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Удаляем строку из таблицы, если удаление прошло успешно
                    this.closest('tr').remove();
                    
                    // Обновляем итоговую сумму
                    const totalPriceElement = document.querySelector('.table-total .table-price strong'); // Элемент с итоговой суммой
                    let totalPrice = parseFloat(totalPriceElement.textContent) || 0;
                    totalPrice -= price; // Вычитаем цену удаленного товара из общей суммы
                    totalPriceElement.textContent = totalPrice.toFixed(2); // Обновляем текст в элементе с итоговой суммой
                } else {
                    console.error('Ошибка:', data.message);
                }
            })
            .catch(error => console.error('Ошибка:', error));
        });
    });
});
