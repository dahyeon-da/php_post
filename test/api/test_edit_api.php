<?php
require_once('../../config.php');

$title = $_POST['title'];
$writer = $_POST['writer'];
$content = $_POST['content'];
$post_seq = $_POST['post_seq'];
$org_file_name = $_POST['org_file_name'];
$file_status = $_POST['file_status'];
$date = date('Y-m-d', time());
$uploadDir = __DIR__ . '/uploads/';

$resultData = array();

// 파일 저장을 위한 랜덤 문자열 생성
function getUniqueFileName($uploadDir, $extension, $length = 10)
{
    function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    do {
        $newFileName = generateRandomString($length) . '.' . $extension;
        $fullPath = $uploadDir . $newFileName;
    } while (file_exists($fullPath));

    return $newFileName;
}

if ($file_status == 'changed') {  // 파일이 변경되었을 때
    $file = $_FILES['file'];

    // 원본파일의 확장자 추출
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

    $file_name = time() . '_' . getUniqueFileName($uploadDir, $ext);
    $target_path = $uploadDir . $file_name;

    // 기존 파일 삭제
    $org_file_path = $uploadDir . $org_file_name;
    if (!$file == null) {
        unlink($org_file_path);
        move_uploaded_file($file['tmp_name'], $target_path);
    }

    $file_array = array(
        'file_name' => $file['name'],
        'file_path' => $target_path,
        'convert_file_name' => $file_name,
        'post_seq' => $post_seq
    );

    $file_sql = 'UPDATE file_table SET file_name = :file_name, file_path = :file_path, convert_file_name = :convert_file_name WHERE FK_post_seq = :post_seq;';
    $file_resultData = $DB->update($file_sql, $file_array);

    if ($file_resultData) {
        $resultData['file_result'] = true;
    } else if (!$file_resultData) {
        $result['file_result'] = false;
    }
}

$array = array(
    "title" => $title,
    "writer" => $writer,
    "content" => $content,
    "date" => $date,
    "post_seq" => $post_seq
);
$sql = "UPDATE post_table SET title = :title, writer = :writer, content = :content, date = :date WHERE post_seq = :post_seq;";

try {
    $post_resultData = $DB->update($sql, $array);
    $resultData['post_result'] = true;

    echo json_encode($resultData);
} catch (Exception $e) {
    $resultData = array(
        "message" => $e
    );
    echo json_encode($resultData);
}
