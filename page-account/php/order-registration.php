<?php
// Параметры подключения к базе данных
$servername = "MySQL-8.2";
$username = "root";
$password = "";
$dbname = "user_database";

// Подключение к базе данных
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения к БД: " . $conn->connect_error);
}

// Получение данных из формы
$product_ids = isset($_POST['product_ids']) ? $_POST['product_ids'] : [];
$product_ids_string = implode(',', $product_ids); // Преобразуем массив в строку

$name = $_POST['name'];
$surname = $_POST['surname'];
$total_price = $_POST['total_price'];
$order_city = $_POST['order_city'];
$phone_number = $_POST['number'];

// Выбор адреса на основе города
if ($order_city === 'Москва') {
    $address = "Улица Тверская, дом 1";
} elseif ($order_city === 'Санкт-Петербург') {
    $address = "Невский проспект, дом 5";
} else {
    $address = "Неизвестный адрес";
}

// SQL-запрос на добавление данных в таблицу
$sql = "INSERT INTO orders (name, surname, totalPrice, address, phoneNumber, product_ids) 
        VALUES ('$name', '$surname', $total_price, '$address', '$phone_number', '$product_ids_string')";

if ($conn->query($sql) === TRUE) {
    echo "Заказ успешно оформлен!";
    header("Location: profile.php");
} else {
    echo "Ошибка: " . $sql . "<br>" . $conn->error;
}

// Закрытие соединения
$conn->close();

?>
