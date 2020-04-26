<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/default/header.php';
?>
<?
$chat_id = 1;
$filter = array(
    'chat_id'=>$chat_id,
    'id'=>4
);
$message_list = Project\Chat::getMessageList($filter, $additional_select = array(), $order = array(), $count = FALSE, $page = 1, $get_count_element = FALSE);
print_r($message_list);
?>
<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/default/footer.php';
?>