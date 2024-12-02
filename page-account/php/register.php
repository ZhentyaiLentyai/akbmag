<?php
$servername = "MySQL-8.2";
$username = "root";
$password = ""; // Задайте пароль MySQL, если есть
$dbname = "user_database";

// Создаем соединение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения к БД: " . $conn->connect_error);
}

// Обработка запроса на регистрацию
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];
    $surname = $_POST['surname'];

    // Подготовка запроса на вставку пользователя
    $stmt = $conn->prepare("INSERT INTO users (username, password, name, surname) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $password, $name, $surname);

    // Выполнение запроса и проверка успешности
    if ($stmt->execute()) {
        echo "Регистрация успешна!";
        
        // Создание таблицы для товаров
        $tableName = "products_" . $conn->insert_id; // Имя таблицы будет включать ID пользователя
        $createTableQuery = "CREATE TABLE $tableName (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            price DECIMAL(10, 2) NOT NULL,
            image LONGBLOB NOT NULL,
            image_type VARCHAR(50) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            product_id INT(11) NOT NULL
        )";

        if ($conn->query($createTableQuery) === TRUE) {
            echo "Таблица товаров создана успешно.";
        } else {
            echo "Ошибка создания таблицы: " . $conn->error;
        }

        header("Location: ../html/login.html");
    } else {
        echo header("Location: ../html/register.html");
    }

    $stmt->close();
}
$conn->close();
?>
