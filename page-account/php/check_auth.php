<?php
session_start();

// Проверка куки
if (isset($_COOKIE['username'])) {
    // Устанавливаем сессии на основе куки
    $_SESSION['username'] = $_COOKIE['username'];

    // Здесь можно также выполнить запрос к базе данных для получения имени и фамилии пользователя
    $servername = "MySQL-8.2";
    $username = "root";
    $password = ""; // Задайте пароль MySQL, если есть
    $dbname = "user_database";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Ошибка подключения к БД: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT name, surname FROM users WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['name'] = $row['name'];
        $_SESSION['surname'] = $row['surname'];
    }
    $stmt->close();
    $conn->close();

    // Перенаправление на личный кабинет
    header("Location: /page-account/php/profile.php");
    exit();
} else {
    // Если кука не найдена, перенаправляем на страницу входа
    header("Location: /page-account/html/login.html");
    exit();
}
?>
