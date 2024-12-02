<?php
session_start(); // Инициализация сессии
// Подключение к базе данных
$servername = "MySQL-8.2";
$username = "root";
$password = ""; // Укажите пароль, если он установлен
$dbname = "batteries";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения к БД: " . $conn->connect_error);
}

// Проверяем, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверка на ошибки загрузки
    if (!isset($_FILES['image']) || $_FILES['image']['error'] != UPLOAD_ERR_OK) {
        die("Ошибка загрузки файла изображения. Код ошибки: " . $_FILES['image']['error']);
    }

    $name = $_POST['name'];
    $price = $_POST['price'];
    $manufacturer = $_POST['manufacturer'];
    $availability_msk = $_POST['availability_msk'];
    $availability_spb = $_POST['availability_spb'];
    $category = $_POST['category'];
    $weight = $_POST['weight'];
    $warranty_period = $_POST['warranty_period'];
    $imageData = file_get_contents($_FILES['image']['tmp_name']);
    $imageType = $_FILES['image']['type'];

    // Подготовка и выполнение SQL-запроса
    $stmt = $conn->prepare("INSERT INTO batteries (name, price, image, image_type, manufacturer, availability_msk, availability_spb, category, weight, warranty_period) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsssiissd", $name, $price, $imageData, $imageType, $manufacturer, $availability_msk, $availability_spb, $category, $weight, $warranty_period);

    if ($stmt->execute()) {
        // Успешно добавлено
        header("Location: page-catalog.php");
    } else {
        echo "Ошибка: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
