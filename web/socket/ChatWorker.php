<? 
/*
* На основе https://timeweb.com/ru/community/articles/chat-na-websocket-ah-1
*  
*/

$path = realpath(__DIR__. '/../../');

require_once $path . '/web/templates/default/header.php';

use Workerman\Lib\Timer;
use Workerman\Worker;

$connections = []; // сюда будем складывать все подключения

// Стартуем WebSocket-сервер на порту 27800
//=== php -c /etc/php/7.2/apache2/php.ini ChatWorker.php start
//$worker = new Worker("websocket://0.0.0.0:27800");
$worker = new Worker("websocket://0.0.0.0:27800");

//=== При подключении
$worker->onConnect = function($connection)
{
    // Эта функция выполняется при подключении пользователя к WebSocket-серверу
    $connection->onWebSocketConnect = function($connection) 
    {
        /*$data_query = array(
            'chat_id'=>1,
            'user_author'=>1,
            'message'=>"Hello World!\n",
        );
        Project\Chat::setMessage($data_query);*/
        
        //echo "Hello World!\n"; // все сообщения выводятся в терминал сервера
        //print_r($_GET);
        //$connection->send("Hello World!"); // а это сообщение будет отправлено клиенту
    };
};

//=== При подключении пользователя к WebSocket-серверу
/*$worker->onConnect = function($connection) use(&$connections)
{
    $connection->onWebSocketConnect = function($connection) use (&$connections)
    {*/
        // Достаём имя пользователя, если оно было указано
        /*if (isset($_GET['userName'])) {
            $originalUserName = preg_replace('/[^a-zA-Zа-яА-ЯёЁ0-9\-\_ ]/u', '', trim($_GET['userName']));
        }
        else {
            $originalUserName = 'Инкогнито';
        }*/
        
        // Половая принадлежность, если указана
        // 0 - Неизвестный пол
        // 1 - М
        // 2 - Ж
        /*if (isset($_GET['gender'])) {
            $gender = (int) $_GET['gender'];
        }
        else {
            $gender = 0;
        }
        
        if ($gender != 0 && $gender != 1 && $gender != 2) 
            $gender = 0;*/
        
        // Цвет пользователя
        /*if (isset($_GET['userColor'])) {
            $userColor = $_GET['userColor'];
        }
        else {
            $userColor = "#000000";
        }*/
                
        // Проверяем уникальность имени в чате
        /*$userName = $originalUserName;
        
        $num = 2;
        do {
            $duplicate = false;
            foreach ($connections as $c) {
                if ($c->userName == $userName) {
                    $userName = "$originalUserName ($num)";
                    $num++;
                    $duplicate = true;
                    break;
                }
            }
        } 
        while($duplicate);*/
        
        // Добавляем соединение в список
        //$connection->userName = $userName;
        //$connection->gender = $gender;
        //$connection->userColor = $userColor;
        /*$connection->pingWithoutResponseCount = 0; // счетчик безответных пингов
        
        $connections[$connection->id] = $connection;
        
        // Собираем список всех пользователей
        $users = [];
        foreach ($connections as $c) {
            $users[] = [
                'user_author' => $c->id,
                //'userName' => $c->userName, 
                //'gender' => $c->gender,
                //'userColor' => $c->userColor
            ];
        }
        
        // Отправляем пользователю данные авторизации
        $messageData = [
            'action' => 'Authorized',
            'user_author' => $connection->id,
            //'userName' => $connection->userName,
            //'gender' => $connection->gender,
            //'userColor' => $connection->userColor,
            'users' => $users
        ];
        $connection->send(json_encode($messageData));
        
        // Оповещаем всех пользователей о новом участнике в чате
        $messageData = [
            'action' => 'Connected',
            'user_author' => $connection->id,
            //'userName' => $connection->userName,
            //'gender' => $connection->gender,
            //'userColor' => $connection->userColor
        ];
        $message = json_encode($messageData);
        
        foreach ($connections as $c) {
            $c->send($message);
        }
    };
};*/

//=== Эта функция выполняется при закрытии соединения
/*$worker->onClose = function($connection) use(&$connections)
{
    if (!isset($connections[$connection->id])) {
        return;
    }
    
    // Удаляем соединение из списка
    unset($connections[$connection->id]);
    
    // Оповещаем всех пользователей о выходе участника из чата
    $messageData = [
        'action' => 'Disconnected',
        'user_author' => $connection->id,
        //'userName' => $connection->userName,
        //'gender' => $connection->gender,
        //'userColor' => $connection->userColor
    ];
    $message = json_encode($messageData);
    
    foreach ($connections as $c) {
        $c->send($message);
    }
};*/

//=== Пинг пользователя
/*$worker->onWorkerStart = function($worker) use (&$connections)
{
    $interval = 5; // пингуем каждые 5 секунд
    Timer::add($interval, function() use(&$connections) {
        foreach ($connections as $c) {
            // Если ответ не пришел 3 раза, то удаляем соединение из списка
            // и оповещаем всех участников об "отвалившемся" пользователе
            if ($c->pingWithoutResponseCount >= 3) {
                unset($connections[$c->id]);
                
                $messageData = [
                    'action' => 'ConnectionLost',
                    'user_author' => $c->id
                ];
                $message = json_encode($messageData);
                
                $c->destroy(); // уничтожаем соединение
                
                foreach ($connections as $c) {
                    $c->send($message);
                }
            }
            else {
                $c->send('{"action":"Ping"}');
                $c->pingWithoutResponseCount++; // увеличиваем счетчик пингов
            }
        }
    });
};*/

//=== При отправке сообщения
$worker->onMessage = function($connection, $message) use (&$connections)
{
    //echo "Message!\n";
    echo $message;
    
    // распаковываем json
    $messageData = json_decode($message, true);
    // проверяем наличие ключа 'toUserId', который используется для отправки приватных сообщений
    //$toUserId = isset($messageData['toUserId']) ? (int) $messageData['toUserId'] : 0;
    $action = isset($messageData['action']) ? $messageData['action'] : '';
    
    print_r($messageData);
    
    if ($action == 'PublicMessage') {
        echo $message;
        
        $data_query = array(
            'chat_id'=>$messageData['chat_id'],
            'user_author'=>$messageData['user_author'],
            'message'=>$messageData['message'],
        );
        Project\Chat::setMessage($data_query);
    }
    
    
    /*if ($action == 'Pong') {
        // При получении сообщения "Pong", обнуляем счетчик пингов
        $connection->pingWithoutResponseCount = 0;
    }
    else {*/
        // Все остальные сообщения дополняем данными об отправителе
        //$messageData['userId'] = $connection->id;
        //$messageData['userName'] = $connection->userName;
        //$messageData['gender'] = $connection->gender;
        //$messageData['userColor'] = $connection->userColor;
        
        // Преобразуем специальные символы в HTML-сущности в тексте сообщения
        //$messageData['text'] = htmlspecialchars($messageData['text']);
        // Заменяем текст заключенный в фигурные скобки на жирный
        // (позже будет описано зачем и почему)
        //$messageData['text'] = preg_replace('/\{(.*)\}/u', '<b>\\1</b>', $messageData['text']);
        
        //if ($toUserId == 0) {
            // Отправляем сообщение всем пользователям
            //$messageData['action'] = 'PublicMessage';
            //foreach ($connections as $c) {
            //    $c->send(json_encode($messageData));
            //}
        /*}
        else {
            $messageData['action'] = 'PrivateMessage';
            if (isset($connections[$toUserId])) {
                // Отправляем приватное сообщение указанному пользователю
                $connections[$toUserId]->send(json_encode($messageData));
                // и отправителю
                $connections->send(json_encode($messageData));
            }
            else {
                $messageData['text'] = 'Не удалось отправить сообщение выбранному пользователю';
                $connection->send(json_encode($messageData));
            }
        }
    }*/
};

Worker::runAll();

?>

<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/default/footer.php';
?>
