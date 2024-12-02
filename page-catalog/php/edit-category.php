<?php
session_start();

// Подключение к базе данных
$servername = "MySQL-8.2";
$username = "root";
$password = "";
$dbname = "batteries";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения к БД: " . $conn->connect_error);
}

// Добавление новой категории
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_category'])) {
    $new_category = $_POST['new_category'];

    if (!empty($new_category)) {
        $stmt = $conn->prepare("INSERT INTO categories (category) VALUES (?)");
        $stmt->bind_param("s", $new_category);
        
        if ($stmt->execute()) {
            header("Location: page-catalog.php"); // Перенаправление на страницу каталога
            exit();
        } else {
            echo "Ошибка: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Удаление категории
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['category'])) {
    $category_to_delete = $_GET['category'];

    $stmt = $conn->prepare("DELETE FROM categories WHERE category = ?");
    $stmt->bind_param("s", $category_to_delete);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]); // Отправляем успешный ответ для AJAX
        exit();
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]); // В случае ошибки
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
