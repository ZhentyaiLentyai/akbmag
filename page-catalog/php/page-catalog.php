<?php
session_start();

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

// Получение данных аккумуляторов
$sql = "SELECT * FROM batteries";
$result = $conn->query($sql);

$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);

$categories = [];
while ($row = $result_categories->fetch_assoc()) {
    $categories[] = $row["category"];
}

// Подключение к базе данных для получения информации о пользователе
$conn_users = new mysqli($servername, $username, $password, "user_database");
if ($conn_users->connect_error) {
    die("Ошибка подключения к БД: " . $conn_users->connect_error);
}

// Получаем информацию о пользователе
$stmt = $conn_users->prepare("SELECT id, name, surname, role FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$stmt->bind_result($user_id, $name, $surname, $role);
$stmt->fetch();
$stmt->close();
$conn_users->close(); // Закрываем соединение с базой данных пользователей
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
    <link rel="stylesheet" href="../../page-catalog/css/aside/filters.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>

    <!-- main -->
    <link rel="stylesheet" href="../../page-main/css/main/main.css">
    <link rel="stylesheet" href="../../page-catalog/css/main/title.css">
    <link rel="stylesheet" href="../../page-catalog/css/main/card-container.css">
    <link rel="stylesheet" href="../../page-catalog/css/main/modal-window.css">
    <link rel="stylesheet" href="../../page-catalog/css/main/add-card.css">
    <link rel="stylesheet" href="../../page-catalog/css/main/edit-card.css">
    <link rel="stylesheet" href="../../page-catalog/css/main/edit-category.css">
    <link rel="stylesheet" href="../../page-catalog/css/aside/filters.css">

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
                    <a class="menu__catalog-link" href="../../page-catalog/html/page-catalog.html">
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
            <div class="filters">
                <h1>Фильтры</h1>
            <!-- Фильтр по цене -->
            <div class="filter">
                <h2>Цена</h2>
                <div id="price-slider"></div>
                <div class="price-labels">
                    <span id="price-min-label">0</span> ₽ - <span id="price-max-label">20000</span> ₽
                </div>
            </div>

            <!-- Фильтр по категории -->
            <div class="filter">
                <h2>Категория</h2>
                <select class="filter-category" id="category-filter">
                    <option value="">Все</option>
                    <?php
                    foreach ($categories as $category) {
                        echo '<option value="' . $category . '">' . $category . '</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Фильтр по наличию -->
            <div class="filter">
                <h2>Наличие</h2>
                <label>
                    <input type="checkbox" id="availability-msk"> Наличие в Москве
                </label>
                <label>
                    <input type="checkbox" id="availability-spb"> Наличие в СПБ
                </label>
            </div>

            <!-- Кнопка для сброса фильтров -->
                <button id="reset-filters" class="filter-button">Сбросить фильтры</button>
            </div>
        </aside>

        <script src="../js/filters.js"></script>

        <main>
            <div class="title">
                <h1>Аккумуляторы для авто</h1>
                <?php if ($role === 'admin'): ?>
                    <div class="button-block">
                        <button id="addCardButton" class="add-button">Добавить карточку</button>
                        <button id="editCategory" class="add-button">Изменить категории</button>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-container">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <a class="card__link" href="../../page-card/php/page-card.php?id=<?php echo $row['id']; ?>">
                            <div class="card" data-id="<?php echo $row['id']; ?>" 
                                data-name="<?php echo $row['name']; ?>"
                                data-price="<?php echo $row['price']; ?>"
                                data-manufacturer="<?php echo htmlspecialchars($row['manufacturer']); ?>"
                                data-availability_msk="<?php echo $row['availability_msk']; ?>" 
                                data-availability_spb="<?php echo $row['availability_spb']; ?>" 
                                data-category="<?php echo htmlspecialchars($row['category']); ?>" 
                                data-weight="<?php echo $row['weight']; ?>" 
                                data-warranty_period="<?php echo $row['warranty_period']; ?>">
                                
                                <img src="data:<?php echo htmlspecialchars($row['image_type']); ?>;base64,<?php echo base64_encode($row['image']); ?>" 
                                alt="<?php echo htmlspecialchars($row['name']); ?>">
                                <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                                <p><strong>Цена:</strong> <?php echo htmlspecialchars($row['price']); ?> ₽</p>

                                <p class='availability availability-msk' style="display: none"><strong>Наличие:</strong> <?php echo $row['availability_msk'] ? 'В наличии' : 'Нет в наличии'; ?></p>
                                <p class='availability availability-spb' style="display: none"><strong>Наличие:</strong> <?php echo $row['availability_spb'] ? 'В наличии' : 'Нет в наличии'; ?></p>
                                
                                <?php if ($role === 'admin'): ?>
                                    <button class="card-button__edit">Редактировать</button>
                                    <button class="card-button__delete">Удалить</button>
                                    

                                    <script src="../js/delete-card.js"></script>
                                    

                                <?php else: ?>
                                    <button class="card-button__basket" data-id="<?php echo $row['id']; ?>">Добавить в корзину</button>

                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Нет доступных аккумуляторов.</p>
                <?php endif; ?>
            </div>
                    
            <script src="../js/add-card-basket.js"></script>

            <!-- Модальное окно добавления карточки -->
            <div id="addCardModal" class="modal">
                <div class="modal__content">
                    <span class="close-button__add-card">&times;</span>
                    <h2>Добавить карточку</h2>
                    <form id="addCardForm" class="addCardForm" method="POST" action="add-card.php" enctype="multipart/form-data">
                        <label for="name">Название:</label>
                        <input type="text" id="name" name="name" required>

                        <label for="price">Цена:</label>
                        <input type="number" id="price" name="price" required step="0.01">

                        <label for="image">Изображение:</label>
                        <input type="file" id="image" name="image" required>

                        <label for="manufacturer">Производитель:</label>
                        <input type="text" id="manufacturer" name="manufacturer">

                        <label for="availability_msk">Наличие msk:</label>
                        <select id="availability_msk" name="availability_msk">
                            <option value="1">В наличии</option>
                            <option value="0">Нет в наличии</option>
                        </select>

                        <label for="availability_spb">Наличие spb:</label>
                        <select id="availability_spb" name="availability_spb">
                            <option value="1">В наличии</option>
                            <option value="0">Нет в наличии</option>
                        </select>

                        <label for="category">Категория:</label>
                        <select name="category" id="category">                            
                            <?php
                            foreach ($categories as $category) {
                                echo '<option value="' . $category . '">' . $category . '</option>';
                            }
                            ?>
                        </select>

                        <label for="weight">Вес:</label>
                        <input type="number" id="weight" name="weight" step="0.01">

                        <label for="warranty_period">Период гарантии (мес):</label>
                        <input type="number" id="warranty_period" name="warranty_period">

                        <button class="save-button" type="submit">Сохранить карточку</button>
                    </form>
                </div>
            </div>
            

            <!-- Модальное окно изменения карточки -->
            <div id="editCardModal" class="modal">
                <div class="modal__content">
                    <span class="close-button__edit-card">&times;</span>
                    <h2>Редактировать карточку</h2>
                    <form id="editCardForm" class="editCardForm"method="POST" action="edit-card.php" enctype="multipart/form-data">
                        <input type="hidden" id="edit_card_id" name="id" required>
                        <label for="edit_name">Название:</label>
                        <input type="text" id="edit_name" name="name" required>

                        <label for="edit_price">Цена:</label>
                        <input type="number" id="edit_price" name="price" required step="0.01">

                        <label for="image">Изображение:</label>
                        <input type="file" id="edit_image" name="image">

                        <label for="edit_manufacturer">Производитель:</label>
                        <input type="text" id="edit_manufacturer" name="manufacturer">

                        <label for="edit_availability_msk">Наличие в Москве:</label>
                        <select id="edit_availability_msk" name="availability_msk">
                            <option value="1">В наличии</option>
                            <option value="0">Нет в наличии</option>
                        </select>

                        <label for="edit_availability_spb">Наличие в Питере:</label>
                        <select id="edit_availability_spb" name="availability_spb">
                            <option value="1">В наличии</option>
                            <option value="0">Нет в наличии</option>
                        </select>

                        <label for="edit_category">Категория:</label>
                        <select name="category" id="edit_category">                            
                            <?php
                            foreach ($categories as $category) {
                                echo '<option value="' . $category . '">' . $category . '</option>';
                            }
                            ?>
                        </select>

                        <label for="edit_weight">Вес:</label>
                        <input type="number" id="edit_weight" name="weight" step="0.01">

                        <label for="edit_warranty_period">Период гарантии (мес):</label>
                        <input type="number" id="edit_warranty_period" name="warranty_period">

                        <input type="hidden" id="edit_url" name="edit_url" value="page-catalog">


                        <button class="save-button" type="submit">Сохранить изменения</button>
                    </form>
                </div>
            </div>
            
            <script src="../js/edit-card.js"></script>

            <!-- Модальное окно изменения категорий -->
            <div id="editCategoryModal" class="modal">
                <div class="modal__content">
                    <span class="close-button__edit-category">&times;</span>
                    <h2>Изменение категорий</h2>
                    <p>Категории:</p>
                    <ul name="category" id="categories-list">                            
                        <?php
                        foreach ($categories as $category) {
                            echo '<li id="category-' . $category . '">' . $category . ' 
                                <span class="delete-category" data-category="' . $category . '">&times</span>
                            </li>';
                        }
                        ?>
                    </ul>
                    <form id="editCategoryForm" class="editCategoryForm" method="POST" action="edit-category.php">

                        <label for="new_category">Новая категория:</label>
                        <input type="text" id="new_category" name="new_category" required>

                        <button class="save-button" type="submit">Добавиь категорию</button>

                    </form>
                </div>
            </div>

            <?php if ($role === 'admin'): ?>                      
                <script src="../js/add-card.js"></script>
                <script src="../js/edit-category.js"></script>
            <?php endif; ?>
        </main>

        <?php
            $conn->close(); // Закрываем соединение с базой данных
        ?>

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
    </div>
</body>