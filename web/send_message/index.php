<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/default/header.php';
?>
<? 
$chat_id=trim(filter_input(INPUT_POST, 'chat_id', FILTER_SANITIZE_STRING));
$user_author=trim(filter_input(INPUT_POST, 'user_author', FILTER_SANITIZE_STRING));
$message=trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING));

$data_query = array(
    'chat_id'=>$chat_id,
    'user_author'=>$user_author,
    'message'=>$message
);
Project\Chat::setMessage($data_query);
?>
<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/default/footer.php';
?>