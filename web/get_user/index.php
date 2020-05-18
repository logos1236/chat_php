<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/default/header.php';
?>
<?
$data_query = array(
    'name'=>'test',
    'password'=>123456,
);
//$result = Project\User::add($data_query);


//$user_list = Project\User::getList('test');
//print_r(end($user_list));

$result = Project\User::auth($data_query);
//print_r($result);
?>
<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/default/footer.php';
?>
