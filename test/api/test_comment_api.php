<?php
require_once('../../config.php');

$post_seq = file_get_contents('php://input');

$array = array(
    'post_seq' => $post_seq
);
$sql = 'SELECT c.comment_date, c.comment_content, u.username FROM comment_table c, user_table u WHERE c.FK_post_seq = :post_seq AND u.seq = c.FK_user_seq ORDER BY comment_date DESC; ';

try{
    $result = $DB -> selectAll($sql, $array);
    $resultData = array(
        'data' => $result, 
        'result' => 'success'
    );

    echo json_encode($resultData);
}catch(Exception $e){
    $resultData = array(
        'message' => $e
    );

    echo json_encode($resultData);
}