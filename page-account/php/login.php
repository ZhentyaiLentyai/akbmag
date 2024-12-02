<?php
session_start();
$servername = "MySQL-8.2";
$username = "root";
$password = ""; // Задайте пароль MySQL, если есть
$dbname = "user_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения к БД: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Получаем данные пользователя из базы данных
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Проверка пароля
        if (password_verify($pass, $row['password'])) {
            // Устанавливаем сессии
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $user;
            $_SESSION['name'] = $row['name'];
            $_SESSION['surname'] = $row['surname'];
            $_SESSION['role'] = $row['role']; // Сохраняем роль в сессии

            // Устанавливаем куку
            setcookie("username", $user, time() + (86400 * 30), "/"); // 30 дней

            header("Location: /page-account/php/profile.php");
        } else {
            // Неправильный пароль
            header("Location: ../html/login.html");
            exit();
        }
    } else {
        // Пользователь не найден
        header("Location: ../html/login.html");
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
