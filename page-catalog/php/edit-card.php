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

// Проверяем, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $manufacturer = $_POST['manufacturer'];
    $availability_msk = $_POST['availability_msk'];
    $availability_spb = $_POST['availability_spb'];
    $category = $_POST['category'];
    $weight = $_POST['weight'];
    $warranty_period = $_POST['warranty_period'];
    $url = $_POST['edit_url'];

    // Проверяем, был ли загружен файл изображения
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        // Получаем данные изображения
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
        $imageType = $_FILES['image']['type'];
    } else {
        // Если файл не был загружен, то оставляем старое изображение
        $imageData = null;
        $imageType = null;
    }

    // Подготовка и выполнение SQL-запроса
    if ($imageData) {
        // Если изображение было загружено
        $stmt = $conn->prepare("UPDATE batteries SET name=?, price=?, image=?, image_type=?, manufacturer=?, availability_msk=?, availability_spb=?, category=?, weight=?, warranty_period=? WHERE id=?");
        $stmt->bind_param("sdsssiissdi", $name, $price, $imageData, $imageType, $manufacturer, $availability_msk, $availability_spb, $category, $weight, $warranty_period, $id);
    } else {
        // Если изображение не было загружено, то обновляем только остальные данные
        $stmt = $conn->prepare("UPDATE batteries SET name=?, price=?, manufacturer=?, availability_msk=?, availability_spb=?, category=?, weight=?, warranty_period=? WHERE id=?");
        $stmt->bind_param("sdssssssd", $name, $price, $manufacturer, $availability_msk, $availability_spb, $category, $weight, $warranty_period, $id);
    }

    if ($stmt->execute()) {
        // Успешно обновлено
        if ($url === 'page-catalog') {
            header("Location: page-catalog.php");
            exit();
        } else if ($url === 'page-card') {
            header("Location: ../../page-card/php/page-card.php?id=$id");
            exit();
        }
    } else {
        echo "Ошибка: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
