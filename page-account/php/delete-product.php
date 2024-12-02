<?php
session_start();
// Подключение к базе данных
$servername = "MySQL-8.2";
$username = "root";
$password = ""; // Укажите пароль, если он установлен
$dbname = "user_database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения к БД: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Проверка на наличие ID
    if (empty($id)) {
        echo json_encode(['status' => 'error', 'message' => 'ID не указан.']);
        exit;
    }

    $table_name = "products_" . $_SESSION['user_id'];
    $stmt = $conn->prepare("DELETE FROM $table_name WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка при удалении записи.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Неверный метод запроса.']);
}