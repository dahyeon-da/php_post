<?php
require_once('../../config.php');
session_start();
// $DB 보유

$data = file_get_contents('php://input');
$data = json_decode($data, true);

$username = $data['username'];
$password = $data['password'];

$array = array(
    'username' => $username
);
$sql = 'SELECT password FROM user_table where username = :username;';
$password = hash('sha256', $password);

try {
    $result = $DB->selectOne($sql, $array);
    if ($result == $password) {
        $resultData = array(
            'result' => 'success',
            'message' => 'Success Login',
        );
        $_SESSION['username'] = $username;

        $resultData = json_encode($resultData);
        echo $resultData;
    } else{
        $resultData = array(
            'result' => 'error', 
            'message' => 'Failed Login'
        );
        echo json_encode($resultData);
    }
} catch (Exception $e) {
    echo $e;
}
?>