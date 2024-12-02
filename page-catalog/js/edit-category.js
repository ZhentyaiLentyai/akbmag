document.addEventListener("DOMContentLoaded", function() {
    // Открытие модального окна
    document.getElementById('editCategory').onclick = function() {
        document.getElementById('editCategoryModal').style.display = 'block';
    };

    // Закрытие модального окна
    document.querySelector('.close-button__edit-category').onclick = function() {
        document.getElementById('editCategoryModal').style.display = 'none';
    };

    document.querySelectorAll('.delete-category').forEach(function(button) {
        button.addEventListener('click', function(event) {
            const category = event.target.dataset.category;
            const listItem = document.getElementById('category-' + category);
            
            if (confirm("Вы уверены, что хотите удалить категорию '" + category + "'?")) {
                // Отправка запроса на удаление категории через AJAX
                fetch('edit-category.php?action=delete&category=' + encodeURIComponent(category))
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            listItem.remove(); // Удаляем категорию из списка
                        } else {
                            alert('Ошибка при удалении категории');
                        }
                    })
                    .catch(error => console.error('Ошибка:', error));
            }
        });
    });
    // Обработчик добавления категории через форму
    document.getElementById('editCategoryForm').onsubmit = function(event) {
        event.preventDefault();

        const newCategoryInput = document.getElementById('new_category');
        const newCategory = newCategoryInput.value.trim();

        if (newCategory) {
            fetch('edit-category.php', {
                method: 'POST',
                body: new FormData(this)
            })
            .then(response => response.text())
            .then(data => {
                // Добавляем категорию в список
                const categoriesList = document.getElementById('categories-list');
                const newCategoryItem = document.createElement('li');
                newCategoryItem.textContent = newCategory + ' ';
                const deleteLink = document.createElement('span');
                deleteLink.textContent = '×';
                deleteLink.classList.add('delete-category');
                deleteLink.dataset.category = newCategory;

                newCategoryItem.appendChild(deleteLink);
                categoriesList.appendChild(newCategoryItem);

                newCategoryInput.value = ''; // Очистка поля ввода
            })
            .catch(error => console.error('Ошибка:', error));
        }
    };
});