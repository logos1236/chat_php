<?php
$src_path = realpath($_SERVER['DOCUMENT_ROOT']. '/../src/')."/"; 

//=== Получение настроек
require_once $src_path . 'Settings.php';

//=== Подключение к базе
require_once $src_path . 'Connection.php';

//=== Пользователь
require_once $src_path . 'Essence/User.php';

//=== Чат
require_once $src_path . 'Essence/Chat.php';