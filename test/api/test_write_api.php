<?php
require_once("../../config.php");

$title = $_POST['title'];
$writer = $_POST['writer'];
$content = $_POST['content'];

$file = $_FILES['file'];
$uploadDir = __DIR__ . '/uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// 파일 저장을 위한 랜덤 문자열 생성
function getUniqueFileName($uploadDir, $extension, $length = 10) {
    function generateRandomString($length){$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++){
        $randomString .= $characters[rand(0, $charactersLength -1)];
    }
        return $randomString;
    }
    do{
        $newFileName = generateRandomString($length).'.'.$extension;
        $fullPath = $uploadDir.$newFileName;
    } while (file_exists($fullPath));

    return $newFileName;
}

// 원본파일의 확장자 추출
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

$fileName = time() . '_' . getUniqueFileName($uploadDir, $ext);
$targetPath = $uploadDir . $fileName;
move_uploaded_file($file['tmp_name'], $targetPath);
$date = date("Y-m-d", time());

$post_array = array(
    'title' => $title,
    'writer' => $writer,
    'content' => $content,
    'date' => $date
);

$file_array = array(
    'fileName' => $file['name'],
    'filePath' => $targetPath,
    'convertFileName' => $fileName
);

try {
    $post_sql = 'INSERT INTO post_table(title, writer, content, date) VALUES (:title, :writer, :content, :date);';
    $post_result = $DB->insert($post_sql, $post_array);
    $int_post_result = (int) $post_result;

    $file_array["FK_post_seq"] = $int_post_result;

    $file_sql = 'INSERT INTO file_table(file_name, file_path, FK_post_seq, convert_file_name) VALUES (:fileName, :filePath, :FK_post_seq, :convertFileName);';
    $file_result = $DB->insert($file_sql, $file_array);

    if ($file_result && $post_result) {
        $resultData = array(
            'result' => 'success',
            'message' => 'Success File, Post Upload', 
            'data_size' => $file['size']
        );

        echo json_encode($resultData);
        exit;
    } else {
        $resultData = array(
            'result' => 'success',
            'message' => 'Failed'
        );

        echo json_encode($resultData);
        exit;
    }
} catch (Exception $e) {
    echo $e;
}
?>