<?php
require_once('../../config.php');

$post_seq = file_get_contents('php://input');

$array = array(
    'post_seq' => $post_seq
);

try{
    $sql = 'SELECT p.title, p.writer, p.content, p.date, f.file_name, f.file_path, f.FK_post_seq, f.convert_file_name FROM post_table p, file_table f WHERE p.post_seq = :post_seq AND f.FK_post_seq = :post_seq; ';
    $resultData = $DB->selectOneRow($sql, $array);

    $resultData = array(
        'data' => $resultData, 
        'result' => 'success',
        'message' => 'Success View Post Detail'
    );

    echo json_encode($resultData);
} catch(Exception $e){
    echo $e;
}
?>