<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/default/header.php';
?>
<? 
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
<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/default/footer.php';
?>