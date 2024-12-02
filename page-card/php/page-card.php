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

$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT id, name, price, image, image_type, manufacturer, availability_msk, availability_spb, category, weight, warranty_period FROM batteries WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->bind_result($id, $name, $price, $image, $image_type, $manufacturer, $availability_msk, $availability_spb, $category, $weight, $warranty_period);
$stmt->fetch();
$stmt->close();

$sql = "SELECT * FROM comments ORDER BY created_at DESC";
$result = $conn->query($sql);

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
    <link rel="stylesheet" href="../../page-catalog/css/main/card-container.css">
    <link rel="stylesheet" href="../../page-catalog/css/main/modal-window.css">
    <link rel="stylesheet" href="../../page-catalog/css/main/edit-card.css">
    <link rel="stylesheet" href="../css/product.css">
    <link rel="stylesheet" href="../css/tab-container.css">
    <link rel="stylesheet" href="../css/comment.css">
    <!-- footer -->
    <link rel="stylesheet" href="../../page-main/css/footer/footer.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $name ?></title>
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
        <div class="product">
            <h2 class="product__name"><?php echo htmlspecialchars($name); ?></h2>
            <div class="product__content" 
                data-id="<?php echo $id; ?>"
                data-name="<?php echo $name; ?>"
                data-price="<?php echo $price; ?>"
                data-manufacturer="<?php echo htmlspecialchars($manufacturer); ?>" 
                data-availability_msk="<?php echo $availability_msk; ?>" 
                data-availability_spb="<?php echo $availability_spb; ?>" 
                data-category="<?php echo htmlspecialchars($category); ?>" 
                data-weight="<?php echo $weight; ?>" 
                data-warranty_period="<?php echo $warranty_period; ?>">
                <img class="product__img" src="data:<?php echo htmlspecialchars($image_type); ?>;base64,<?php echo base64_encode($image); ?>">
                <div class="product__info">
                    <p><strong>Цена:</strong> <?php echo htmlspecialchars($price); ?> ₽</p>

                    <p class="availability availability-msk" style="display: none"><strong>Наличие:</strong> <?php echo htmlspecialchars($availability_msk) ? 'В наличии' : 'Нет в наличии'; ?></p>
                    <p class="availability availability-spb" style="display: none"><strong>Наличие:</strong> <?php echo htmlspecialchars($availability_spb) ? 'В наличии' : 'Нет в наличии'; ?></p>
                    
                    <p><strong>Производитель:</strong> <?php echo htmlspecialchars($manufacturer); ?></p>
                    <p><strong>Категория:</strong> <?php echo htmlspecialchars($category); ?></p>
                    <p><strong>Вес:</strong> <?php echo htmlspecialchars($weight); ?></p>
                    <p><strong>Срок гарантии:</strong> <?php echo htmlspecialchars($warranty_period); ?></p>
                    
                    <?php if (!isset($_SESSION['role'])): ?>
                        <button class="card-button__basket" data-id="<?php echo $id; ?>">Добавить в корзину</button>
                    <?php elseif ( $_SESSION['role'] === 'admin'): ?>
                        <button class="card-button__edit">Редактировать</button>
                    <?php else: ?>
                        <button class="card-button__basket" data-id="<?php echo $id; ?>">Добавить в корзину</button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="tab-container">
                <ul class="tab-menu">
                    <li class="tab-item" data-tab="availability">Наличие</li>
                    <li class="tab-item active" data-tab="reviews">Отзывы</li>
                    <div class="tab-item-none"></div>
                </ul>

                <div class="tab-content" id="availability">
                    <h2>Наличие</h2>
                    <?php if (!isset($_SESSION['role']) || $_SESSION['role'] === 'user'): ?>
                        <p>В Москве: <?php echo htmlspecialchars($availability_msk) ? 'В наличии' : 'Нет в наличии'; ?></p>
                        <p>В Санкт-Петебурге: <?php echo htmlspecialchars($availability_spb) ? 'В наличии' : 'Нет в наличии'; ?></p>
                        <?php elseif ($_SESSION['role'] === 'admin'): ?>
                        <p>2</p>
                        
                    <?php else: ?>
                    <?php endif; ?>

                    <div id="map" style="width: 100%; height: 400px;"></div>

                    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
                    <script src="../js/map.js"></script>

                </div>
                <div class="tab-content active" id="reviews">
                    <div class="product__comment">
                        <h2>Отзывы</h2>
                        <?php if (!isset($_SESSION['role'])): ?>
                            <p>Чтобы оставить отзыв, нужно авторизоваться.</p>

                        <?php elseif ($_SESSION['role'] === 'admin'): ?>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    if ($row['product_id'] == $id) {
                                        echo "<div class='comment-item' data-id='" . $row['id'] . "'>";
                                        echo "<p class='comment-author'><strong>" . htmlspecialchars($row['username']) . ":</strong></p>";
                                        echo "<p class='comment-date'>" . date("d-m-Y", strtotime($row['created_at'])) . "</p>";
                                        echo "<p class='comment-text'>" . htmlspecialchars($row['comment']) . "</p>";
                                        echo "<button class='comment-delete-button'>Удалить</button>";
                                        echo "</div>";
                                    }
                                }
                            } else {
                                echo "Пока отзывов нет.";
                            }
                            ?>
                        
                        <?php else: ?>
                            <form id="commentForm" class="comment-form" method="POST" action="add-comment.php">
                                <label class="comment-title" for="comment-text">Оставить отзыв:</label>
                                <textarea class="comment-textarea" id="comment-text" name="comment" required></textarea>
                                <input type="hidden" id="product_id" name="product_id" value="<?php echo htmlspecialchars($id); ?>">
                                <button class="comment-button" type="submit">Отправить</button>
                            </form>
                        <?php endif; ?>
                        
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                if ($row['product_id'] == $id) {
                                    echo "<div class='comment-item'>";
                                    echo "<p class='comment-author'><strong>" . htmlspecialchars($row['username']) . ":</strong></p>";
                                    echo "<p class='comment-date'>" . date("d-m-Y", strtotime($row['created_at'])) . "</p>";
                                    echo "<p class='comment-text'>" . htmlspecialchars($row['comment']) . "</p>";
                                    echo "</div>";
                                }
                            }
                        } else {
                            echo "Пока отзывов нет.";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="editCardModal" class="modal">
            <div class="modal__content">
                <span class="close-button__edit-card">&times;</span>
                <h2>Редактировать карточку</h2>
                <form id="editCardForm" class="editCardForm"method="POST" action="../../page-catalog/php/edit-card.php" enctype="multipart/form-data">
                    <input type="hidden" id="edit_card_id" name="id" required>
                    <label for="edit_name">Название:</label>
                    <input type="text" id="edit_name" name="name" required>

                    <label for="edit_price">Цена:</label>
                    <input type="number" id="edit_price" name="price" required step="0.01">

                    <label for="image">Изображение:</label>
                        <input type="file" id="image" name="image">

                    <label for="edit_manufacturer">Производитель:</label>
                    <input type="text" id="edit_manufacturer" name="manufacturer">

                    <label for="edit_availability_msk">Наличие:</label>
                    <select id="edit_availability_msk" name="availability_msk">
                        <option value="1">В наличии</option>
                        <option value="0">Нет в наличии</option>
                    </select>

                    <label for="edit_availability_spb">Наличие:</label>
                    <select id="edit_availability_spb" name="availability_spb">
                        <option value="1">В наличии</option>
                        <option value="0">Нет в наличии</option>
                    </select>

                    <label for="edit_category">Категория:</label>
                        <select name="category" id="edit_category">                            
                            <?php
                            while($row = $result_categories->fetch_assoc()) {
                                echo '<option value="' . $row["category"] . '">' . $row["category"] . '</option>';
                            }
                            ?>
                        </select>
                     
                    <label for="edit_weight">Вес:</label>
                    <input type="number" id="edit_weight" name="weight" step="0.01">

                    <label for="edit_warranty_period">Период гарантии (мес):</label>
                    <input type="number" id="edit_warranty_period" name="warranty_period">

                    <input type="hidden" id="edit_url" name="edit_url" value="page-card">

                    <button class="save-button" type="submit">Сохранить изменения</button>
                </form>
            </div>
        </div>

        <script src="../../page-catalog/js/add-card-basket.js"></script>
        <script src="../../page-catalog/js/edit-card.js"></script>
        <script src="../js/tab-container.js"></script>

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
    </div>
</body>