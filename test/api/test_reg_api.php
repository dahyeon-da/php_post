<?php
require_once('../../config.php');

session_start();

$data = file_get_contents('php://input');
$data = json_decode($data, true);

// 회원가입에서 받아온 데이터
$username = $data['username'];
$password = $data['password'];
$email = $data['email'];

$require_once = ('../../.env');
$password = hash($hash_val, $password);

$array = array(
    'username' => $username,
    'password' => $password,
    'email' => $email
);
$sql = 'INSERT INTO user_table(username, password, email) VALUES(:username, :password, :email);';

try {
    $result = $DB->insert($sql, $array);

    $resultData = array(
        'result' => 'success',
        'message' => 'Success Register'
    );
    $_SESSION['username'] = $username;

    $resultData = json_encode($resultData);
    echo $resultData;
} catch (Exception $e) {
    $resultData = array(
        'result' => 'error', 
        'message' => 'Failed Register'
    );

    echo json_encode($resultData);
}

?>