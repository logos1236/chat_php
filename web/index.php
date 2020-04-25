<? 
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';

//Project\User::createTable();

//Project\Chat::createTable();
//Project\Chat::dropTable();

/*$data_query = array(
    'chat_id'=>1,
    'user_author'=>1,
    'message'=>'Test'
);
Project\Chat::setMessage($data_query);*/

$message_list = Project\Chat::getMessageList($filter = array(), $additional_select = array(), $order = array(), $count = FALSE, $page = 1, $get_count_element = FALSE);
print_r($message_list);
?>