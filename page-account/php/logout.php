<?php
session_start();
session_unset();
session_destroy();

// Удаляем куку
setcookie("username", "", time() - 3600, "/"); // Устанавливаем время действия в прошлом

header("Location: /page-account/html/login.html");
exit();
?>
