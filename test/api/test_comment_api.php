<?php
require_once('../../config.php');
date_default_timezone_set('Asia/Seoul');

$data = file_get_contents('php://input');
$data = json_decode($data, true);

$post_seq = $data['post_seq'];
$func = $data['func'];

$array = array(
    'post_seq' => $post_seq
);
$sql = 'SELECT c.comment_date, c.comment_content, u.username FROM comment_table c, user_table u WHERE c.FK_post_seq = :post_seq AND u.seq = c.FK_user_seq ORDER BY comment_date DESC; ';

switch ($func) {
    case 'read':
        try {
            $result = $DB->selectAll($sql, $array);
            $resultData = array(
                'data' => $result,
                'result' => 'success'
            );

            echo json_encode($resultData);
            break;
        } catch (Exception $e) {
            $resultData = array(
                'message' => $e
            );

            echo json_encode($resultData);
            break;
        }
    case 'write':
        try{
            $comment_content = $data['comment_content'];
            $user_id = $data['user_id'];
            $date = date("Y-m-d H:i:s");
            
            $sql = 'SELECT seq FROM user_table WHERE user_id = :user_id;';
            $array = array(
                'user_id' => $user_id
            );
            $result = $DB->selectOneRow($sql, $array);  

            $array = array(
                'post_seq' => $post_seq, 
                'user_seq' => $result, 
                'comment_date' => $date, 
                'comment_content' => $comment_content
            );

            $sql = 'INSERT INTO comment_table(FK_post_seq, FK_user_seq, comment_date, comment_content) VALUES (:post_seq, :user_seq, :comment_date, :comment_content);';
            $result = $DB -> insert($sql, $array);

            $resultData = array(
                'result' => 'success', 
                'message' => '댓글 작성이 완료되었습니다.'
            );

            echo json_encode($resultData);
            break;
        } catch (Exception $e) {
            $resultData = array(
                'message' => '댓글 작성에 실패했습니다.'
            );

            echo json_encode($resultData);
            break;
        }
}
