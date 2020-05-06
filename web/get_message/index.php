<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/default/header.php';
?>
<?
$chat_id=trim(filter_input(INPUT_POST, 'chat_id', FILTER_SANITIZE_STRING));
$count=((int)trim(filter_input(INPUT_POST, 'count', FILTER_SANITIZE_STRING)) > 0) ? (int)trim(filter_input(INPUT_POST, 'count', FILTER_SANITIZE_STRING)) : FALSE;
$id=(int)trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING));


$filter = array(
    'chat_id'=>$chat_id
);

if ($id > 0) {
    $filter['id'] = $id;
}

$message_list = Project\Chat::getMessageList($filter, $additional_select = array(), $order = array(), $count, $page = 1, $get_count_element = FALSE);

header('Content-type: application/json; charset=utf-8');
echo json_encode($message_list);
?>
<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/default/footer.php';
?>
