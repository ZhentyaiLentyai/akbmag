<?php
session_start(); // Начинаем сессию

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
    exit();
}

// Подключение к базе данных
$servername = "MySQL-8.2";
$username = "root";
$password = ""; // Укажите пароль к MySQL, если есть
$batteries_db = "batteries";

$conn = new mysqli($servername, $username, $password, $batteries_db);
if ($conn->connect_error) {
    die("Ошибка подключения к БД: " . $conn->connect_error);
}

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT id, name, price, image, image_type FROM batteries WHERE id = ?"; // Обратите внимание на добавление image_url в SELECT
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->bind_result($id, $name, $price, $image, $image_type);
$stmt->fetch();
$stmt->close();
$conn->close();

// Проверяем, установлен ли идентификатор пользователя
if (!isset($_SESSION['user_id'])) {
    echo json_encode(array("error" => "User ID not set in session."));
    exit();
}

// Получаем user_id из сессии
$user_id = $_SESSION['user_id'];
// Создаем название таблицы
$table_name = "products_" . intval($user_id); // Используем intval для предотвращения SQL-инъекций

$product_db = 'user_database'; 
$conn2 = new mysqli($servername, $username, $password, $product_db);

if ($conn2->connect_error) {
    die("Connection to DB2 failed: " . $conn2->connect_error);
}

// Убедитесь, что таблица существует, прежде чем вставлять данные
$insert_sql = "INSERT INTO `$table_name` (product_id, name, price, image, image_type) VALUES (?, ?, ?, ?, ?)"; // Добавьте image_url в INSERT
$insert_stmt = $conn2->prepare($insert_sql);
$insert_stmt->bind_param("isdss", $id, $name, $price, $image, $image_type); 
$insert_stmt->execute();
$insert_stmt->close();
$conn2->close();
header('Content-Type: application/json');
echo json_encode(['status' => 'success']);
?>
