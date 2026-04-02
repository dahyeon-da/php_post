<?php
require_once('../../config.php');

session_start();

$data = file_get_contents('php://input');
$data = json_decode($data, true);

// 회원가입에서 받아온 데이터
$username = $data['username'];
$password = $data['password'];
$email = $data['email'];
$user_id = $data['user_id'];

$password = hash('sha256', $password);

try {
    $array = array(
        'user_id' => $user_id
    );
    $sql = 'select COUNT(*) FROM user_table WHERE user_id=:user_id;';
    $result = $DB->selectAll($sql, $array);
    $count = (int)$result[0]['COUNT(*)'];

    if ($count == 0) {
        try {
            // 회원가입 내용을 DB에 넣기위한 array, sql 변수
            $array = array(
                'username' => $username,
                'password' => $password,
                'email' => $email,
                'user_id' => $user_id
            );
            $sql = 'INSERT INTO user_table(username, password, email, user_id) VALUES(:username, :password, :email, :user_id);';
            $result = $DB->insert($sql, $array);

            $_SESSION['user_id'] = $user_id;

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
    } else {
        $resultData = array(
            'result' => 'duplication', 
            'message' => '중복된 ID가 있습니다.'
        );

        echo json_encode($resultData);
    }
} catch (Exception $e) {
    $resultData = array(
        'result' => 'error',
        'message' => $e->getMessage()
    );

    echo json_encode($resultData);
}
