<?php
session_start(); // Начинаем сессию

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['username'])) {
    header("Location: login.html"); // Если не авторизован, перенаправляем на страницу входа
    exit();
}

// Подключение к базе данных
$servername = "MySQL-8.2";
$username = "root";
$password = ""; // Укажите пароль к MySQL, если есть
$dbname = "user_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения к БД: " . $conn->connect_error);
}

// Получаем информацию о пользователе
$stmt = $conn->prepare("SELECT id, name, surname, role FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$stmt->bind_result($user_id, $name, $surname, $role);
$stmt->fetch();
$stmt->close();

$table_name = "products_" . $user_id;

// Получаем данные корзины для текущего пользователя
if($role === 'user'){
    $basket_items = [];
    $query = "SELECT id, name, image, image_type, price, product_id FROM $table_name";
    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $basket_items[] = $row;
        }
    } else {
        echo "Ошибка при выполнении запроса: " . $conn->error;
    }
}

if($role === 'admin'){
    $orders_items = [];
    $orders_query = "SELECT id, name, surname, totalPrice, address, phoneNumber, product_ids FROM orders";
    $orders_result = $conn->query($orders_query);
    if ($orders_result) {
        while ($row = $orders_result->fetch_assoc()) {
            $orders_items[] = $row;
        }
    } else {
        echo "Ошибка при выполнении запроса: " . $conn->error;
    }
}
$conn->close();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../page-main/css/page/page.css">
    <!-- header -->
    <link rel="stylesheet" href="../../page-main/css/header/header.css">
    <link rel="stylesheet" href="../../page-main/css/header/navigation.css">
    <link rel="stylesheet" href="../../page-main/css/header/menu.css">
    <!-- aside -->
    <link rel="stylesheet" href="../../page-main/css/aside/aside.css">
    <link rel="stylesheet" href="../../page-main/css/aside/avtoakb.css">
    <!-- main -->
    <link rel="stylesheet" href="../../page-main/css/main/main.css">
    <link rel="stylesheet" href="../../page-account/css/profile.css">
    <link rel="stylesheet" href="../../page-account/css/basket.css">
    <link rel="stylesheet" href="../../page-account/css/orders.css">
    <link rel="stylesheet" href="../../page-account/css/orderRegistration.css">
    <link rel="stylesheet" href="../../page-catalog/css/main/modal-window.css">

    <!-- footer -->
    <link rel="stylesheet" href="../../page-main/css/footer/footer.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akbmag</title>
</head>
<body>
    <div class="page">
        <header>
            <div class="navigation">
                <div class="navigation__container">
                    <div class="navigation__left">
                        <ul class="navigation__menu">
                            <li class="navigation__city">
                                <a href="" id="selected-city">Москва</a>
                                <ul class="navigation__city-menu">
                                    <li><a href="#" class="navigation__city-option" data-city="Москва">Москва</a></li>
                                    <li><a href="#" class="navigation__city-option" data-city="Санкт-Петербург">Санкт-Петербург</a></li>
                                </ul>
                            </li>
                            <li class="navigation__dropdown">
                                <button id="dropdown-btn">Меню</button>
                                <ul class="navigation__dropdown-menu">
                                    <li><a href="">О компании</a></li>
                                    <li><a href="">Оптом</a></li>
                                    <li><a href="">Гарантия</a></li>
                                    <li><a href="">Акции</a></li>
                                    <li><a href="">Вакансии</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <script src="../../page-main/js/navigation-menu.js"></script>

                    <div class="navigation__right">
                        <input class="navigation__right-search" placeholder="Поиск по сайту">
                        <a href=""><img class="navigation__search-img" src="../../page-main/img/search.svg" alt="search"></a>
                        <a href="../../page-account/php/check_auth.php"><img class="navigation__account-img" src="../../page-main/img/account.jpg" alt="basket"></a>
                    </div>
                </div>
            </div>
            <div class="menu">
                <div class="menu__left">
                    <div class="menu__logo">
                        <a href="../../page-main/html/page-main.html"><img src="../../page-main/img/akbmag.svg" alt="АКБМАГ"></a>
                        <p>Магазин аккумуляторов</p>
                    </div>
                    <a class="menu__catalog-link" href="../../page-catalog/php/page-catalog.php">
                        <div class="menu__button-catalog">Каталог</div>
                    </a>
                    <div class="menu__contacts">
                        <p>
                            <a href="">
                                <span class="menu__phone">+7 (977) 777-77-77</span>
                            </a>
                        </p>
                        <a href="" class="menu__callback">Заказать обратный звонок</a>
                    </div>
                </div>
                <div class="menu__links">
                    <ul>
                        <li><a href="">Доставка и оплата</a></li>
                        <li><a href="">Статус заказа</a></li>
                        <li><a href="">Адреса и контакты</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <script src="../../page-main/js/city.js"></script>

        <aside>
            <div class="avtoakb">
                <p>Автомобильные аккумуляторы</p>
                <ul>
                    <li class="avtoakb__brand"><a href="">HOWTER</a></li>
                    <ul>
                        <li class="avtoakb__series"><a href="">HOWTER AGM</a></li>
                        <li class="avtoakb__series"><a href="">HOWTER EFB</a></li>
                    </ul>
                    <li class="avtoakb__brand">
                        <a href="">AKBMAX</a></li>
                    <ul>
                        <li class="avtoakb__series"><a href="">AKBMAX PLUS</a></li>
                        <li class="avtoakb__series"><a href="">AKBMAX ST</a></li>
                    </ul>
                </ul>
            </div>
        </aside>

        <main>
            <div class="profile">
                <div class="profile__title">
                    <h1><?php echo htmlspecialchars($name) . " " . htmlspecialchars($surname); ?></h1>
                    <a href="../php/logout.php">Выйти</a>
                </div>
                <?php if ($role === 'admin'): ?>
                    <div class="profile-container">
                        <p>Заказы клиентов</p>
                        <div class="orders">
                            <table class="orders__table">
                                <tr>
                                    <th class="table-products">Товары</th>
                                    <th class="table-user-name">Имя пользователя</th>
                                    <th class="table-price-order">Цена заказа</th>
                                    <th class="table-adress">Адрес выдачи</th>
                                    <th class="table-number">Номер</th>
                                </tr>
                                <?php 
                                foreach ($orders_items as $item): 
                                ?>
                                    <tr data-id="<?php echo htmlspecialchars($item['id']); ?>">
                                        <td class="table-products">
                                            <?php
                                            // Разделение строки product_ids на массив
                                            $product_ids = explode(',', $item['product_ids']); // Разделение по запятой

                                            // Подключение к базе данных batteries
                                            $dbname = "batteries";
                                            $conn2 = new mysqli($servername, $username, $password, $dbname);
                                            if ($conn2->connect_error) {
                                                die("Ошибка подключения к БД: " . $conn2->connect_error);
                                            }

                                            // Генерация ссылок с именами продуктов
                                            foreach ($product_ids as $product_id) {
                                                // Убедимся, что product_id не пустой (на случай ошибок в данных)
                                                $product_id = trim($product_id);
                                                if (!empty($product_id)) {
                                                    // Получаем name из базы данных batteries, сравнивая product_id с id в таблице products
                                                    $stmt = $conn2->prepare("SELECT name FROM batteries WHERE id = ?");
                                                    $stmt->bind_param("i", $product_id);  // Используем параметр $product_id для поиска
                                                    $stmt->execute();
                                                    $stmt->bind_result($product_name);
                                                    $stmt->fetch();
                                                    $stmt->close();

                                                    // Если имя найдено, вывести его как ссылку
                                                    if (!empty($product_name)) {
                                                        echo '<a href="../../page-card/php/page-card.php?id=' . htmlspecialchars($product_id) . '">';
                                                        echo htmlspecialchars($product_name);
                                                        echo '</a><br>'; // Перенос строки для отображения ссылок в столбик
                                                    } else {
                                                        // Если имя не найдено, вывести ID
                                                        echo '<a href="../../page-card/php/page-card.php?id=' . htmlspecialchars($product_id) . '">';
                                                        echo "Продукт #" . htmlspecialchars($product_id);
                                                        echo '</a><br>';
                                                    }
                                                }
                                            }

                                            // Закрытие соединения с базой данных
                                            $conn2->close();
                                            ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($item['name']); ?>
                                            <?php echo htmlspecialchars($item['surname']); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($item['totalPrice']); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($item['address']); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($item['phoneNumber']); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="profile-container">
                        <p>Ваша корзина</p>
                        <div class="basket">
                            <table class="basket__table">
                                <tr>
                                    <th class="table-img">Изображение</th>
                                    <th class="table-name">Название продукта</th>
                                    <th class="table-price">Цена</th>
                                    <th class="table-price"></th>
                                </tr>
                                <?php 
                                $total_price = 0;
                                foreach ($basket_items as $item): 
                                    $total_price += $item['price'];
                                ?>
                                    <tr data-id="<?php echo htmlspecialchars($item['id']); ?>">
                                        <td>
                                            <img src="data:<?php echo htmlspecialchars($item['image_type']); ?>;base64,<?php echo base64_encode($item['image']); ?>" class="table-img">
                                        </td>
                                        <td class="table-name">
                                            <a href="../../page-card/php/page-card.php?id=<?php echo $item['product_id']; ?>">
                                                <?php echo htmlspecialchars($item['name']); ?>
                                            </a>
                                        </td>
                                        <td class="table-price">
                                            <?php echo htmlspecialchars($item['price']); ?>
                                        </td>
                                        <td class="table-delete">
                                            <button class="table-button__delete">Удалить</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="table-total">
                                    <td colspan="2" class="table-name"><strong>Итоговая стоимость:</strong></td>
                                    <td class="table-price"><strong><?php echo htmlspecialchars($total_price); ?></strong></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                        <script src="../js/delete-product.js"></script>
                        <button id="form-order" class="form-order">Оформить заказ</button>
                    </div>
                <?php endif; ?>
            </div>

            <div id="orderRegistration" class="modal">
                <div class="modal__content">
                    <span class="close-orderRegistration">&times;</span>
                    <h2>Оформление заказа</h2>
                    <p class="your-order"><strong>Ваш заказ:</strong></p>
                    <div class="order-list">
                        <ul name="order" id="order-list">                            
                            <?php
                            foreach ($basket_items as $item) {
                                echo '<li>' . htmlspecialchars($item['name']) . ' - ' . htmlspecialchars($item['price']) . ' ₽</li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <p class="total-price"><strong>Сумма заказа:</strong> <?php echo htmlspecialchars($total_price);?> ₽</p>
                    <form id="orderRegistrationForm" class="orderRegistrationForm" method="POST" action="order-registration.php">
                    <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                    <input type="hidden" name="surname" value="<?php echo htmlspecialchars($surname); ?>">
                    <input type="hidden" name="total_price" value="<?php echo htmlspecialchars($total_price); ?>">
                    <?php
                    foreach ($basket_items as $item) {
                        echo '<input type="hidden" name="product_ids[]" value="' . htmlspecialchars($item['product_id']) . '">';
                    }
                    ?>


                        <label for="order_city"><strong>Выберите город:</strong></label>
                        <select name="order_city" id="order_city" required>
                            <option value="Москва">Москва</option>
                            <option value="Санкт-Петербург">Санкт-Петербург</option>
                        </select>

                        <p id="delivery-address" class="delivery-address">Адрес получения: Улица Тверская, дом 1</p>

                        <label for="number"><strong>Введите Ваш номер:</strong></label>
                        <input type="tel" id="number" name="number" pattern="[0-9]*" required title="Введите номер телефона!">

                        <button class="order-button" type="submit">Заказать</button>

                    </form>
                </div>
            </div>

            <script src="../js/order-registration.js"></script>
            <script src="../js/address.js"></script>

        </main>

        <footer>
            <div class="footer__container">
                <div class="footer__text">
                    <ul>
                        <li><a href="">О компании</a></li>
                        <li><a href="">Помощь</a></li>
                        <li><a href="">Адреса и контакты</a></li>
                        <li><a href="">Партнерская программа</a></li>
                        <li><a href="">Вакансии</a></li>
                        <li><a href="">Пользовательское соглашение</a></li>
                        <li><a href="">Политика конфиденциальности</a></li>
                    </ul>
                </div>
                <img src="../../page-main/img/footer__warranty.png">
            </div>
        </footer>