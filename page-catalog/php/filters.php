<?php
// Подключение к базе данных
$servername = "MySQL-8.2"; // Замените на ваш сервер
$username = "root"; // Ваше имя пользователя
$password = ""; // Ваш пароль
$dbname = "batteries"; // Название вашей базы данных

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения к БД: " . $conn->connect_error);
}

// Строим SQL-запрос с учетом фильтров
$sql = "SELECT * FROM batteries WHERE 1";

// Фильтры
if (isset($_GET['price_min']) && $_GET['price_min'] != "") {
    $price_min = $_GET['price_min'];
    $sql .= " AND price >= '$price_min'";
}
if (isset($_GET['price_max']) && $_GET['price_max'] != "") {
    $price_max = $_GET['price_max'];
    $sql .= " AND price <= '$price_max'";
}
if (isset($_GET['availability']) && $_GET['availability'] != "") {
    $availability = $_GET['availability'];
    $sql .= " AND availability = '$availability'";
}
if (isset($_GET['manufacturer']) && $_GET['manufacturer'] != "") {
    $manufacturer = $_GET['manufacturer'];
    $sql .= " AND manufacturer LIKE '%$manufacturer%'";
}
if (isset($_GET['category']) && $_GET['category'] != "") {
    $category = $_GET['category'];
    $sql .= " AND category LIKE '%$category%'";
}
if (isset($_GET['weight_min']) && $_GET['weight_min'] != "") {
    $weight_min = $_GET['weight_min'];
    $sql .= " AND weight >= '$weight_min'";
}
if (isset($_GET['weight_max']) && $_GET['weight_max'] != "") {
    $weight_max = $_GET['weight_max'];
    $sql .= " AND weight <= '$weight_max'";
}
if (isset($_GET['warranty_min']) && $_GET['warranty_min'] != "") {
    $warranty_min = $_GET['warranty_min'];
    $sql .= " AND warranty_period >= '$warranty_min'";
}
if (isset($_GET['warranty_max']) && $_GET['warranty_max'] != "") {
    $warranty_max = $_GET['warranty_max'];
    $sql .= " AND warranty_period <= '$warranty_max'";
}

// Выполнение SQL-запроса
$result = $conn->query($sql);

// Выводим результаты
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div class='product'>";
        echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
        echo "<p>Цена: " . htmlspecialchars($row['price']) . " ₽</p>";
        echo "<p>Производитель: " . htmlspecialchars($row['manufacturer']) . "</p>";
        echo "<p>Категория: " . htmlspecialchars($row['category']) . "</p>";
        echo "<p>Вес: " . htmlspecialchars($row['weight']) . " кг</p>";
        echo "<p>Срок гарантии: " . htmlspecialchars($row['warranty_period']) . " мес</p>";
        echo "</div>";
    }
} else {
    echo "Нет товаров, соответствующих фильтрам.";
}

// Закрытие соединения
$conn->close();
?>
