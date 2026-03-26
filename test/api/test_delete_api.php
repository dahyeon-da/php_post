<?php
require_once('../../config.php');

$post_seq = file_get_contents('php://input');

$array = array('post_seq' => $post_seq);

// 파일 삭제하기
$file_select_sql = 'SELECT convert_file_name FROM file_table WHERE FK_post_seq = :post_seq;';

$uploadDir = __DIR__ . '/uploads/';
$file_name = $DB->selectOne($file_select_sql, $array);

// file이 존재할때만 unlink 수행
if (!$file == null) {
    $unlink = unlink($uploadDir . $file_name);
}

try {
    $post_delete_sql = 'DELETE FROM post_table WHERE post_seq = :post_seq;';
    $file_delete_sql = 'DELETE FROM file_table WHERE FK_post_seq = :post_seq;';

    $post_delete_resultData = $DB->delete($post_delete_sql, $array);
    $file_delete_resultData = $DB->delete($file_delete_sql, $array);

    if ($post_delete_resultData && $file_delete_resultData) {
        $resultData = array(
            'result' => 'success',
            'message' => 'Success Delete Post & File'
        );

        echo json_encode($resultData);
    } else {
        $resultData = array(
            'result' => 'failed',
            'message' => '삭제에 실패하였습니다.'
        );

        echo json_encode($resultData);
    }
} catch (Exception $e) {
    $resultData = array(
        'message' => $e
    );
    echo json_encode($resultData);
}
