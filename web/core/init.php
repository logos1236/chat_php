<?php
$path = realpath(__DIR__. '/../../');

//=== Получение настроек
require_once $path . '/src/Settings.php';

//=== Подключение к базе
require_once $path . '/src/Connection.php';

//=== Пользователь
require_once $path . '/src/Essence/User.php';

//=== Чат
require_once $path . '/src/Essence/Chat.php';

//=== Сокеты
require_once $path. '/src/Workerman-master/vendor/autoload.php';