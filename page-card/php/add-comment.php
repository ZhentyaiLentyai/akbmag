<?php
session_start();

// Подключение к базе данных
$servername = "MySQL-8.2";
$username = "root";
$password = "";
$dbname = "batteries";  // Укажите ваше имя базы данных

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения к БД: " . $conn->connect_error);
}

// Проверка, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $username = $_SESSION['name'] . ' ' . $_SESSION['surname'];
    $comment = $_POST['comment'];

    // Вставка данных в таблицу
    $stmt = $conn->prepare("INSERT INTO comments (product_id, username, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $product_id, $username, $comment);

    if ($stmt->execute()) {
        header("Location: page-card.php?id=$product_id");
    } else {
        echo "Ошибка: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
